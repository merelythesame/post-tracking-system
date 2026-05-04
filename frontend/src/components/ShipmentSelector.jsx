import React from 'react';
import { Listbox } from '@headlessui/react';

/** @module ShipmentSelector */
/**
 * @typedef {Object} Shipment
 * @property {number} id - Unique shipment identifier
 * @property {string} address - Destination address of the shipment
 */

/**
 * A dropdown selector component for choosing a shipment from a list.
 * Built on top of Headless UI's Listbox for accessibility.
 *
 * @component
 * @param {Object} props
 * @param {Shipment[]} props.shipments - List of available shipments to select from
 * @param {Shipment|null} props.selectedShipment - Currently selected shipment, or null if none selected
 * @param {function(Shipment): void} props.setSelectedShipment - Callback fired when the user selects a shipment
 * @returns {JSX.Element}
 *
 * @example
 * const shipments = [
 *   { id: 1, address: "Kyiv, Khreshchatyk 1" },
 *   { id: 2, address: "Lviv, Rynok Square 5" },
 * ];
 *
 * <ShipmentSelector
 *   shipments={shipments}
 *   selectedShipment={shipments[0]}
 *   setSelectedShipment={(s) => console.log(s)}
 * />
 */

export default function ShipmentSelector({ shipments, selectedShipment, setSelectedShipment }) {
    return (
        <div className="mb-6">
            <Listbox value={selectedShipment} onChange={setSelectedShipment}>
                <Listbox.Button className="w-full p-2 border rounded shadow-sm">{selectedShipment ? `Shipment #${selectedShipment.id}` : 'Select a shipment'}</Listbox.Button>
                <Listbox.Options className="mt-1 border rounded shadow">
                    {shipments.map((shipment) => (
                        <Listbox.Option key={shipment.id} value={shipment} className="p-2 hover:bg-gray-100 cursor-pointer">
                            Shipment #{shipment.id} - {shipment.address}
                        </Listbox.Option>
                    ))}
                </Listbox.Options>
            </Listbox>
        </div>
    );
}