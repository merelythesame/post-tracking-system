import React from 'react';
import {unixToDatetimeLocal} from "./dateConvertors.js";

/** @module TrackingDetails */
/**
 * @typedef {Object} Shipment
 * @property {number} id - Unique shipment identifier
 * @property {number} sendOffice - ID of the post office that sends the shipment
 * @property {number} receiveOffice - ID of the post office that receives the shipment
 */

/**
 * @typedef {Object} TrackingStatus
 * @property {number} shipment_id - ID of the associated shipment
 * @property {string} status - Current status of the shipment (e.g. "In Transit", "Delivered")
 * @property {string} location - Current physical location of the shipment
 * @property {number|string} sendAt - Unix timestamp of when the shipment was sent
 * @property {number|string} arriveAt - Unix timestamp of when the shipment arrived
 * @property {string} send_at - Raw send date string (used for empty check)
 * @property {string} arrive_at - Raw arrive date string (used for empty check)
 */

/**
 * @typedef {Object} PostOffice
 * @property {number} id - Unique post office identifier
 * @property {string} name - Name of the post office
 * @property {string} address - Street address of the post office
 * @property {string} city - City where the post office is located
 * @property {string} postalCode - Postal code of the post office
 */

/**
 * Displays full tracking details for a given shipment, including
 * current status, send/arrive timestamps, sending branch,
 * current location, and receiving branch information.
 *
 * @component
 * @param {Object} props
 * @param {Shipment} props.shipment - The shipment to display tracking info for
 * @param {TrackingStatus[]} props.trackingStatuses - List of all tracking statuses
 * @param {PostOffice[]} props.postOffices - List of all post offices
 * @returns {JSX.Element}
 *
 * @example
 * <TrackingDetails
 *   shipment={{ id: 1, sendOffice: 2, receiveOffice: 5 }}
 *   trackingStatuses={[
 *     { shipment_id: 1, status: "In Transit", location: "Kyiv Hub",
 *       sendAt: 1710000000, arriveAt: 1710100000,
 *       send_at: "2024-03-10", arrive_at: "2024-03-11" }
 *   ]}
 *   postOffices={[
 *     { id: 2, name: "Kyiv Central", address: "Khreshchatyk 1", city: "Kyiv", postalCode: "01001" },
 *     { id: 5, name: "Lviv Main", address: "Rynok Square 5", city: "Lviv", postalCode: "79000" }
 *   ]}
 * />
 */

export default function TrackingDetails({ shipment, trackingStatuses, postOffices }) {
    const tracking = trackingStatuses.find(t => t.shipment_id === shipment.id);
    const sendOffice = postOffices.find(p => p.id === shipment.sendOffice);
    const receiveOffice = postOffices.find(p => p.id === shipment.receiveOffice);

    return (
        <>
            <div className="bg-white p-4 rounded shadow mb-6">
                <h2 className="text-xl font-bold mb-2">Tracking Details</h2>
                <p>Status: {tracking?.status || 'Unknown'}</p>
                {tracking?.send_at !== '' ?
                    <div>
                        <p>Send At: {unixToDatetimeLocal(tracking?.sendAt) || 'N/A'}</p>
                    </div>
                    : ''
                }
                {tracking?.arrive_at !== '' ?
                    <div>
                        <p>Arrive At: {unixToDatetimeLocal(tracking?.arriveAt) || 'N/A'}</p>
                    </div>
                    : ''
                }
             </div>
            <div className="flex flex-wrap gap-6 items-start">
                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Sending branch:</p>
                    <p>{sendOffice?.name || 'Unknown'}</p>
                    <p>{sendOffice?.address || 'Unknown'}</p>
                    <p>{sendOffice?.city || 'Unknown'}</p>
                    <p>Postal Code: {sendOffice?.postalCode || 'Unknown'}</p>
                </div>

                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Current location:</p>
                    <p>{tracking?.location || 'Unsent'}</p>
                </div>

                <div className="flex-1 min-w-[250px] bg-gray-50 p-3 rounded-lg text-sm">
                    <p className="text-gray-600 font-medium">Receiving branch:</p>
                    <p>{receiveOffice?.name || 'Unknown'}</p>
                    <p>{receiveOffice?.address || 'Unknown'}</p>
                    <p>{receiveOffice?.city || 'Unknown'}</p>
                    <p>Postal Code: {receiveOffice?.postalCode || 'Unknown'}</p>
                </div>
            </div>

        </>
    );
}

