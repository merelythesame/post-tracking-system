import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Autocomplete from "react-google-autocomplete";

export default function AdminPostOffices() {
    const [offices, setOffices] = useState([]);
    const [formData, setFormData] = useState({ name: '', address: '', city: '', postal_code: '' });
    const [showAddForm, setShowAddForm] = useState(false);
    const [editingOffice, setEditingOffice] = useState(null);

    useEffect(() => {
        fetchPostOffices();
    }, []);

    const fetchPostOffices = async () => {
        try {
            const res = await axios.get('http://localhost:8000/post-office', { withCredentials: true });
            setOffices(res.data);
        } catch {
            toast.error('Failed to load post offices.');
        }
    };

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleAddSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('http://localhost:8000/post-office', formData, { withCredentials: true });
            toast.success('Post office created.');
            setFormData({ name: '', address: '', city: '', postal_code: '' });
            setShowAddForm(false);
            fetchPostOffices();
        } catch {
            toast.error('Failed to add post office.');
        }
    };

    const handleEditSubmit = async (e) => {
        e.preventDefault();

        const hasChanged =
            formData.name !== editingOffice.name ||
            formData.address !== editingOffice.address ||
            formData.city !== editingOffice.city ||
            formData.postal_code !== editingOffice.postal_code;

        if (!hasChanged) {
            toast.error("No changes detected.");
            return;
        }

        const payload = {};
        if (formData.name !== editingOffice.name) payload.name = formData.name;
        if (formData.address !== editingOffice.address) payload.address = formData.address;
        if (formData.city !== editingOffice.city) payload.city = formData.city;
        if (formData.postal_code !== editingOffice.postal_code) payload.postal_code = formData.postal_code;

        try {
            await axios.patch(`http://localhost:8000/post-office/${editingOffice.id}`, payload, { withCredentials: true });
            toast.success('Post office updated.');
            setEditingOffice(null);
            fetchPostOffices();
        } catch {
            toast.error('Failed to update post office.');
        }
    };


    const handleDelete = async (id) => {
        if (!window.confirm('Delete this post office?')) return;
        try {
            await axios.delete(`http://localhost:8000/post-office/${id}`, { withCredentials: true });
            toast.success('Deleted successfully.');
            fetchPostOffices();
        } catch {
            toast.error('Failed to delete post office.');
        }
    };

    return (
        <div className="p-6 bg-gray-50 min-h-screen">
            <ToastContainer position="top-right" autoClose={3000} />
            <div className='flex justify-between'>
                <h1 className="text-3xl font-bold mb-6">Post Offices Management</h1>

                <button
                    onClick={() => setShowAddForm(!showAddForm)}
                    className="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    {showAddForm ? 'Hide' : 'Add New'}
                </button>
            </div>


            {showAddForm && (
                <form onSubmit={handleAddSubmit} className="bg-white p-6 rounded-xl shadow-md mb-6 space-y-4">
                    <h2 className="text-xl font-semibold">New Post Office</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Name"  onChange={handleChange} required className="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900  outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        <Autocomplete
                            apiKey="AIzaSyBZEHh-vdK2z0VSdw_WgfuKPenZF1fC1GQ"
                            onPlaceSelected={(place) => {
                                const components = place.address_components || [];

                                const getComponent = (types) => {
                                    const comp = components.find(c => types.every(t => c.types.includes(t)));
                                    return comp ? comp.long_name : '';
                                };

                                setFormData(prev => ({
                                    ...prev,
                                    address: place.formatted_address || '',
                                    city: getComponent(['locality']) || getComponent(['administrative_area_level_1']),
                                    postal_code: getComponent(['postal_code']),
                                }));
                            }}
                            options={{
                                types: ['address'],
                                componentRestrictions: { country: 'ua' },
                            }}
                            className="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900  outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                            placeholder="Autocomplete Address"
                        />

                        <input type="text" name="city" placeholder="City"  onChange={handleChange} required className="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900  outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        <input type="text" name="postalCode" placeholder="Postal Code"  onChange={handleChange} required className="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900  outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>
                    <button type="submit" className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add</button>
                </form>
            )}

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {offices.map(office => (
                    <div key={office.id} className="bg-white p-6 rounded-xl shadow-md space-y-2">
                        <h3 className="text-xl font-semibold">{office.name}</h3>
                        <p><strong>Address:</strong> {office.address}</p>
                        <p><strong>City:</strong> {office.city}</p>
                        <p><strong>Postal Code:</strong> {office.postalCode}</p>
                        <div className="flex gap-2 mt-2">
                            <button
                                onClick={() => {
                                    setEditingOffice(office);
                                    setFormData({
                                        name: office.name,
                                        address: office.address,
                                        city: office.city,
                                        postal_code: office.postalCode
                                    });
                                }}
                                className="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                            >
                                Edit
                            </button>
                            <button
                                onClick={() => handleDelete(office.id)}
                                className="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                ))}
            </div>

            {editingOffice && (
                <div className="fixed inset-0 backdrop-blur-sm flex justify-center items-center z-50">
                    <div className="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                        <h2 className="text-xl font-semibold mb-4">Edit Post Office</h2>
                        <form onSubmit={handleEditSubmit} className="space-y-4">
                            <input type="text" name="name" value={formData.name} onChange={handleChange} required className="border p-2 w-full flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                            <Autocomplete
                                apiKey="AIzaSyBZEHh-vdK2z0VSdw_WgfuKPenZF1fC1GQ"
                                defaultValue={formData.address}
                                onPlaceSelected={(place) => {
                                    const components = place.address_components || [];

                                    const getComponent = (types) => {
                                        const comp = components.find(c => types.every(t => c.types.includes(t)));
                                        return comp ? comp.long_name : '';
                                    };

                                    setFormData(prev => ({
                                        ...prev,
                                        address: place.formatted_address || '',
                                        city: getComponent(['locality']) || getComponent(['administrative_area_level_1']),
                                        postalCode: getComponent(['postal_code']),
                                    }));
                                }}
                                options={{
                                    types: ['address'],
                                    componentRestrictions: { country: 'ua' },
                                }}
                                className="border p-2 w-full flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                placeholder="Autocomplete Address"
                            />

                            <input type="text" name="city" value={formData.city} onChange={handleChange} required className="border p-2 w-full flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                            <input type="text" name="postal_code" value={formData.postal_code} onChange={handleChange} required className="border p-2 w-full flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                            <div className="flex justify-end gap-4 pt-2">
                                <button type="button" onClick={() => setEditingOffice(null)} className="text-gray-600 hover:underline">
                                    Cancel
                                </button>
                                <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
