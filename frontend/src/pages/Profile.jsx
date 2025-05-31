import {useEffect, useState} from 'react';
import ToggleInput from '../components/ToggleInput.jsx';
import axios from "axios";
import {toast, ToastContainer} from "react-toastify";

export default function ProfilePage() {
    const [user, setUser] = useState(null);
    const [originalUser, setOriginalUser] = useState(null);
    const userId = JSON.parse(localStorage.getItem('user'))?.id;

    useEffect(() => {
        axios.get(`http://localhost:8000/users/${userId}`, { withCredentials: true })
            .then(response => {
                setUser(response.data);
                setOriginalUser(response.data);
            })
            .catch(err => {
                console.log(err);
            });
    }, [userId]);

    if (!user) return <div>Loading...</div>;

    const handleChange = (e) => {
        const { name, value } = e.target;
        setUser(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        const dataToSend = {};

        for (const key in user) {
            if (key === 'password' && !user[key]) {
                continue;
            }

            if (!originalUser || user[key] !== originalUser[key]) {
                dataToSend[key] = user[key];
            }
        }

        if (Object.keys(dataToSend).length === 0) {
            toast.info('No changes to save.');
            return;
        }

        console.log('Sending update:', dataToSend);

        axios.patch(`http://localhost:8000/users/${userId}`, dataToSend, { withCredentials: true })
            .then(response => {
                toast.success(response.data.message || 'Profile updated successfully');
                setOriginalUser(user);
            })
            .catch(error => {
                if (error.response) {
                    toast.error(error.response.data.message || 'Update failed');
                } else {
                    toast.error('Network error or server not reachable');
                }
            });
    };

    return (
        <>
            <ToastContainer position="top-right" autoClose={3000} />
            <form onSubmit={handleSubmit} className="max-w-4xl mx-auto p-4">
                <div className="space-y-12">
                    <div className="border-b border-gray-900/10 pb-12">
                        <h2 className="text-base font-semibold text-gray-900">Profile</h2>
                        <p className="mt-1 text-sm text-gray-600">Update your personal information.</p>

                        <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <ToggleInput label="First Name" type="text" id="name" name="name" value={user.name} handle={handleChange} className="sm:col-span-3" />
                            <ToggleInput label="Last Name" type="text" id="surname" name="surname" value={user.surname} handle={handleChange} className="sm:col-span-3" />
                            <ToggleInput label="Email" type="email" id="email" name="email" value={user.email} handle={handleChange} className="sm:col-span-3" />
                            <ToggleInput label="Phone Number" type="tel" id="phone_number" name="phone_number" value={user.phone_number} handle={handleChange} className="sm:col-span-3" />
                            <ToggleInput label="Password" type="password" id="password" name="password" value={user.password} handle={handleChange} className="sm:col-span-3" />
                        </div>
                    </div>

                    <div className="mt-6 flex items-center justify-end gap-x-6">
                        <button type="button" className="text-sm font-semibold text-gray-900">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </>

    );
}
