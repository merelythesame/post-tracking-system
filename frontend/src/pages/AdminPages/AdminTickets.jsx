import React, { useEffect, useState } from 'react';
import axios from 'axios';

export default function AdminTickets() {
    const [tickets, setTickets] = useState([]);
    const [loading, setLoading] = useState(true);
    const [responseMap, setResponseMap] = useState({});
    const [statusMap, setStatusMap] = useState({});

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

    const handleRespond = async (ticketId) => {
        try {
            await axios.patch(
                `http://localhost:8000/support-tickets/${ticketId}`,
                {
                    response: responseMap[ticketId] || '',
                    status: statusMap[ticketId] || 'open',
                },
                { withCredentials: true }
            );
            fetchTickets();
        } catch (err) {
            console.error('Failed to update ticket:', err);
        }
    };

    const handleDelete = async (ticketId) => {
        if (!window.confirm('Are you sure you want to delete this ticket?')) return;
        try {
            await axios.delete(`http://localhost:8000/support-tickets/${ticketId}`, { withCredentials: true });
            fetchTickets();
        } catch (err) {
            console.error('Failed to delete ticket:', err);
        }
    };

    return (
        <div className="max-w-5xl mx-auto py-10 px-4">
            <h1 className="text-2xl font-bold mb-6">Admin Support Ticket Panel</h1>
            {loading ? (
                <p>Loading...</p>
            ) : (
                <ul className="space-y-6">
                    {tickets.length === 0 ? (
                        <p className="text-gray-500">No support tickets available.</p>
                    ) : (
                        tickets.map((ticket) => (
                            <li key={ticket.id} className="bg-white p-6 rounded shadow border border-gray-200">
                                <div className="mb-2">
                                    <p className="font-medium text-gray-800">Subject: {ticket.subject}</p>
                                    <p className="text-sm text-gray-600">Message: {ticket.message}</p>
                                    <p className="text-sm text-gray-400">User ID: {ticket.user_id}</p>
                                    <p className="text-sm text-gray-400">
                                        Status:{' '}
                                        <span className={`font-semibold ${ticket.status === 'closed' ? 'text-red-500' : 'text-green-600'}`}>
                                            {ticket.status}
                                        </span>
                                    </p>
                                </div>

                                {ticket.status === 'open' ?
                                <textarea
                                    placeholder="Write a response..."
                                    className="w-full border border-gray-300 rounded-md p-2 mb-2"
                                    rows={3}
                                    value={responseMap[ticket.id] ?? ticket.response}
                                    onChange={(e) => setResponseMap({ ...responseMap, [ticket.id]: e.target.value })}
                                />: ''}

                                <div className="flex items-center gap-4">
                                    <select
                                        value={statusMap[ticket.id] ?? ticket.status}
                                        onChange={(e) => setStatusMap({ ...statusMap, [ticket.id]: e.target.value })}
                                        className="border p-2 rounded"
                                    >
                                        <option value="open">Open</option>
                                        <option value="closed">Closed</option>
                                    </select>

                                    <button
                                        onClick={() => handleRespond(ticket.id)}
                                        className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                    >
                                        {ticket.status === 'open' ? 'Submit Response': 'Confirm status'}
                                    </button>
                                    <button
                                        onClick={() => handleDelete(ticket.id)}
                                        className="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-600"
                                    >
                                        Delete
                                    </button>
                                </div>

                                {ticket.response && (
                                    <div className="mt-3 text-sm text-gray-700">
                                        <strong>Previous Response:</strong> {ticket.response}
                                    </div>
                                )}
                            </li>
                        ))
                    )}
                </ul>
            )}
        </div>
    );
}
