import { useState } from 'react';
import { PencilIcon } from 'lucide-react';
import {Button} from "@headlessui/react";

/** @module ToggleInput */
/**
 * @typedef {"text"|"password"|"email"|"number"} InputType
 */

/**
 * A toggleable input field that displays a read-only value by default
 * and switches to an editable input when the pencil icon is clicked.
 *
 * @component
 * @param {Object} props
 * @param {string} props.label - Label text displayed above the field
 * @param {string} props.value - Current value of the input field
 * @param {InputType} props.type - HTML input type (e.g. "text", "password")
 * @param {string} props.id - HTML id attribute for the input and its label
 * @param {string} props.name - HTML name attribute for the input (used in form submission)
 * @param {function(React.ChangeEvent<HTMLInputElement>): void} props.handle - onChange handler for the input
 * @param {string} [props.className="sm:col-span-3"] - Optional Tailwind class for the wrapper div
 * @returns {JSX.Element}
 *
 * @example
 * const [email, setEmail] = useState("user@example.com");
 *
 * <ToggleInput
 *   label="Email"
 *   value={email}
 *   type="email"
 *   id="email"
 *   name="email"
 *   handle={(e) => setEmail(e.target.value)}
 * />
 *
 * @example
 * // Password field — displays dots when not editing
 * <ToggleInput
 *   label="Password"
 *   value={password}
 *   type="password"
 *   id="password"
 *   name="password"
 *   handle={(e) => setPassword(e.target.value)}
 * />
 */

export default function ToggleInput({label, value, type, id, name, handle, className = "sm:col-span-3"}) {
    const [showInput, setShowInput] = useState(false);

    const getDisplayValue = () => {
        if (type === 'password') return '••••••••';
        return value;
    };

    return (
        <div className={`${className}`}>
            <label htmlFor={id} className="block text-sm font-medium text-gray-900">
                {label}
            </label>
            <div className="mt-2 flex items-center gap-2">
                {showInput ? (
                    <input
                        type={type}
                        id={id}
                        name={name}
                        value={value}
                        onChange={handle}
                        className="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                    />
                ) : (
                    <p className="flex-1 text-sm text-gray-700">{getDisplayValue()}</p>
                )}
                <Button type="button" onClick={() => setShowInput(prev => !prev)} size="icon" variant="outline">
                    <PencilIcon width={16} height={16} />
                </Button>
            </div>
        </div>
    );
}
