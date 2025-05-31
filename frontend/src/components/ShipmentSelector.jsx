import React from 'react';
import { Listbox } from '@headlessui/react';

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