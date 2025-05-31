import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';

export default function AdminUsers() {
    const [users, setUsers] = useState([]);

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async () => {
        try {
            const response = await axios.get('http://localhost:8000/users', { withCredentials: true });
            const roleUsers = response.data.filter(user => user.role === 'ROLE_USER');
            setUsers(roleUsers);
        } catch (err) {
            toast.error('Failed to fetch users.');
            console.error(err);
        }
    };

    const handleDelete = async (userId) => {
        if (!window.confirm('Are you sure you want to delete this user?')) return;
        try {
            await axios.delete(`http://localhost:8000/users/${userId}`, { withCredentials: true });
            setUsers(users.filter(user => user.id !== userId));
            toast.success('User deleted successfully.');
        } catch (err) {
            toast.error('Failed to delete user.');
            console.error(err);
        }
    };

    return (
        <div className="p-6 bg-gray-50 min-h-screen">
            <ToastContainer position="top-right" autoClose={3000} />
            <h1 className="text-2xl font-bold mb-6">Users Management</h1>
            {users.length === 0 ? (
                <p className="text-gray-500">No users with role user.</p>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {users.map(user => (
                        <div key={user.id} className="p-6 bg-white rounded-xl shadow-md flex flex-col gap-2">
                            <h2 className="text-xl font-semibold">{user.name} {user.surname}</h2>
                            <p className="text-sm text-gray-600"><strong>Email:</strong> {user.email}</p>
                            <p className="text-sm text-gray-600"><strong>Phone:</strong> {user.phoneNumber}</p>
                            <div className='flex justify-center'>
                                <button
                                    onClick={() => handleDelete(user.id)}
                                    className="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-600"
                                >
                                    Delete User
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
