import { useState } from 'react';
import { PencilIcon } from 'lucide-react';
import {Button} from "@headlessui/react";

export default function ToggleInput({
                                        label,
                                        value,
                                        type,
                                        id,
                                        name,
                                        handle,
                                        className = "sm:col-span-3"
                                    }) {
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
