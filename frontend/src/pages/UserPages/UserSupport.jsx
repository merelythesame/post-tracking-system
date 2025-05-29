import React, { useEffect, useState } from 'react';
import axios from 'axios';

export default function UserSupport() {
    const [tickets, setTickets] = useState([]);
    const [subject, setSubject] = useState('');
    const [message, setMessage] = useState('');
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [showForm, setShowForm] = useState(false);

    const userId = JSON.parse(localStorage.getItem('user'))?.id;

    useEffect(() => {
        fetchTickets();
    }, []);

    const fetchTickets = async () => {
        try {
            const res = await axios.get('http://localhost:8000/support-tickets', { withCredentials: true });
            setTickets(res.data);
            setLoading(false);
        } catch (err) {
            console.error('Failed to fetch tickets:', err);
            setLoading(false);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!subject || !message) return;

        setSubmitting(true);
        try {
            await axios.post(
                'http://localhost:8000/support-tickets',
                {
                    user_id: userId,
                    subject,
                    message,
                    status: 'open',
                },
                { withCredentials: true }
            );
            setSubject('');
            setMessage('');
            setShowForm(false);
            fetchTickets();
        } catch (err) {
            console.error('Failed to submit ticket:', err);
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <div className="max-w-4xl mx-auto py-10 px-4">
            <h1 className="text-2xl font-bold mb-6">Support Tickets</h1>

            {!showForm ? (
                <div className="mb-6 bg-blue-50 border border-blue-200 p-4 rounded">
                    <p className="text-sm text-blue-700 mb-2">
                        Need help? You can create a support ticket and our team will respond shortly.
                    </p>
                    <button
                        onClick={() => setShowForm(true)}
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Create New Ticket
                    </button>
                </div>
            ) : (
                <form onSubmit={handleSubmit} className="bg-white p-6 rounded shadow mb-10">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-xl font-semibold">New Ticket</h2>
                        <button
                            type="button"
                            onClick={() => setShowForm(false)}
                            className="text-sm text-gray-500 hover:underline"
                        >
                            Cancel
                        </button>
                    </div>
                    <div className="mb-4">
                        <label className="block text-sm font-medium text-gray-700">Subject</label>
                        <input
                            type="text"
                            value={subject}
                            onChange={(e) => setSubject(e.target.value)}
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label className="block text-sm font-medium text-gray-700">Message</label>
                        <textarea
                            value={message}
                            onChange={(e) => setMessage(e.target.value)}
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            rows={4}
                            required
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        disabled={submitting}
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        {submitting ? 'Sending...' : 'Submit Ticket'}
                    </button>
                </form>
            )}

            <div className="bg-white p-6 rounded shadow">
                <h2 className="text-xl font-semibold mb-4">Your Tickets</h2>
                {loading ? (
                    <p>Loading...</p>
                ) : tickets.filter(t => t.user_id === userId).length === 0 ? (
                    <p className="text-gray-500">You have no support tickets yet.</p>
                ) : (
                    <ul className="divide-y divide-gray-200">
                        {tickets
                            .filter((ticket) => ticket.user_id === userId)
                            .map((ticket) => (
                                <li key={ticket.id} className="py-4">
                                    <p className="font-medium text-gray-800">Subject: {ticket.subject}</p>
                                    <p className="text-sm text-gray-600">Request: {ticket.message}</p>
                                    {ticket.response !== ''?<p className="text-sm text-gray-600">Response: {ticket.response}</p>:''}
                                    <p className="text-sm text-gray-400">
                                        Status:{' '}
                                        <span
                                            className={`font-semibold ${
                                                ticket.status === 'closed' ? 'text-red-500' : 'text-green-600'
                                            }`}
                                        >
                                            {ticket.status}
                                        </span>
                                    </p>
                                </li>
                            ))}
                    </ul>
                )}
            </div>
        </div>
    );
}
