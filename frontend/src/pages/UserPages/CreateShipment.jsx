import { useState, useEffect } from 'react';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

export default function CreateShipment() {
    const [user, setUser] = useState(null);
    const [formData, setFormData] = useState({
        email: '',
        receiverName: '',
        weight: '',
        type: '',
        sendOffice: '',
        receiveOffice: ''
    });

    const [postOffices, setPostOffices] = useState([]);
    const userId = JSON.parse(localStorage.getItem('user'))?.id;

    useEffect(() => {
        axios.get('http://localhost:8000/post-office', { withCredentials: true })
            .then(res => setPostOffices(res.data))
            .catch(err => console.error('Error loading post offices:', err));
    }, []);

    useEffect(() => {
        axios.get(`http://localhost:8000/users/${userId}`, { withCredentials: true })
            .then(response => setUser(response.data))
            .catch(err => console.log(err));
    }, [userId]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        const shipmentData = {
            user_id: userId,
            receiver_name: formData.receiverName,
            sender_name: user.name + ' ' + user.surname,
            weight: formData.weight,
            type: formData.type,
            send_office: formData.sendOffice,
            receive_office: formData.receiveOffice
        };

        try {
            try {
                const receiverResponse = await axios.get(`http://localhost:8000/users/${formData.email}`, {
                    withCredentials: true
                });
                shipmentData.receiver_id = receiverResponse.data.id;
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    toast.warn("Receiver not found. Proceeding without receiverId.");
                } else {
                    throw error;
                }
            }

            const shipmentResponse = await axios.post('http://localhost:8000/shipments', shipmentData, {
                withCredentials: true
            });

            const shipmentId = shipmentResponse.data.id;

            await axios.post('http://localhost:8000/tracking-status', {
                shipment_id: shipmentId,
            }, { withCredentials: true });

            toast.success("Shipment and tracking created successfully!");
        } catch (error) {
            toast.error("Failed to create shipment.");
        }
    };

    const filteredSendOffices = postOffices.filter(office => office.id !== parseInt(formData.receiveOffice));
    const filteredReceiveOffices = postOffices.filter(office => office.id !== parseInt(formData.sendOffice));

    return (
        <>
            <ToastContainer position="top-right" autoClose={3000} />
            <div className="max-w-2xl mx-auto p-6 bg-white rounded shadow">
                <h2 className="text-2xl font-bold mb-4">Create Shipment</h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Receiver Email</label>
                        <input
                            type="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Receiver Name</label>
                        <input
                            type="text"
                            name="receiverName"
                            value={formData.receiverName}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <input
                            type="number"
                            name="weight"
                            value={formData.weight}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Type</label>
                        <input
                            type="text"
                            name="type"
                            value={formData.type}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Sending branch</label>
                        <select
                            name="sendOffice"
                            value={formData.sendOffice}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        >
                            <option value="">Select a post office</option>
                            {filteredSendOffices.map((office) => (
                                <option key={office.id} value={office.id}>
                                    {office.name} - {office.city}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Receiving branch</label>
                        <select
                            name="receiveOffice"
                            value={formData.receiveOffice}
                            onChange={handleChange}
                            required
                            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        >
                            <option value="">Select a post office</option>
                            {filteredReceiveOffices.map((office) => (
                                <option key={office.id} value={office.id}>
                                    {office.name} - {office.city}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="flex justify-end">
                        <button
                            type="submit"
                            className="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </>
    );
}
