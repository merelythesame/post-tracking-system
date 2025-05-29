import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

export default function Register() {
    const [formData, setFormData] = useState({
        name: "",
        surname: "",
        email: "",
        phoneNumber: "",
        password: ""
    });

    const navigate = useNavigate();

    const handleChange = (e) => {
        setFormData(prev => ({
            ...prev,
            [e.target.name]: e.target.value
        }));
    };

    const registerHandler = (e) => {
        e.preventDefault();

        axios.post("http://localhost:8000/users", formData)
            .then(res => {
                console.log(res);
                toast.success(res.data.message || "Registered successfully!");
                setTimeout(() => navigate("/"), 2000);
            })
            .catch(err => {
                toast.error(err.response?.data?.message || "Registration failed");
            });
    };

    return (
        <>
            <ToastContainer position="top-right" autoClose={3000} />
            <div className="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                    <img
                        alt="Your Company"
                        src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                        className="mx-auto h-10 w-auto"
                    />
                    <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">
                        Create your account
                    </h2>
                </div>

                <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form onSubmit={registerHandler} className="space-y-6">

                        {[
                            { label: "First Name", name: "name", type: "text" },
                            { label: "Last Name", name: "surname", type: "text" },
                            { label: "Email address", name: "email", type: "email" },
                            { label: "Phone Number", name: "phoneNumber", type: "tel" },
                            { label: "Password", name: "password", type: "password" },
                        ].map(({ label, name, type }) => (
                            <div key={name}>
                                <label htmlFor={name} className="block text-sm/6 font-medium text-gray-900">
                                    {label}
                                </label>
                                <div className="mt-2">
                                    <input
                                        id={name}
                                        name={name}
                                        type={type}
                                        required
                                        value={formData[name]}
                                        onChange={handleChange}
                                        className="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    />
                                </div>
                            </div>
                        ))}

                        <div>
                            <button
                                type="submit"
                                className="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Register
                            </button>
                        </div>
                    </form>

                    <p className="mt-10 text-center text-sm/6 text-gray-500">
                        Already have an account?{' '}
                        <a href="/" className="font-semibold text-indigo-600 hover:text-indigo-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </>
    );
}
