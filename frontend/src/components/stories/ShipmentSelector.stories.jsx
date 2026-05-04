import ShipmentSelector from '../ShipmentSelector';
import { useState } from 'react';

const shipments = [
  { id: 1, address: 'Kyiv, Khreshchatyk 1', receiverName: 'John Doe',    weight: 2.5, type: 'Parcel', status: 'In Transit', sendOffice: { name: 'Kyiv Central' },  receiveOffice: { name: 'Lviv Main' } },
  { id: 2, address: 'Lviv, Rynok Square 5', receiverName: 'Jane Smith',  weight: 0.8, type: 'Letter', status: 'Delivered',  sendOffice: { name: 'Odesa Hub' },    receiveOffice: { name: 'Kyiv Central' } },
  { id: 3, address: 'Odesa, Derybasivska 10', receiverName: 'Mike Brown',weight: 5.0, type: 'Cargo',  status: 'Pending',    sendOffice: { name: 'Lviv Main' },    receiveOffice: { name: 'Kharkiv Post' } },
];

function ShipmentsLayout({ initialIndex = null, shipmentList = shipments }) {
  const [selected, setSelected] = useState(
    initialIndex !== null ? shipmentList[initialIndex] : null
  );

  return (
    <div className="max-w-6xl mx-auto px-4 py-10">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">My Shipments</h1>
      </div>

      <ShipmentSelector
        shipments={shipmentList}
        selectedShipment={selected}
        setSelectedShipment={setSelected}
      />

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {shipmentList.map((shipment) => (
          <div
            key={shipment.id}
            onClick={() => setSelected(shipment)}
            className={`bg-white rounded-2xl shadow-md p-6 border transition cursor-pointer
              ${selected?.id === shipment.id
                ? 'border-indigo-400 ring-2 ring-indigo-200'
                : 'border-gray-100 hover:shadow-lg'
              }`}
          >
            <h2 className="text-xl font-semibold text-indigo-600 mb-2">
              Shipment #{shipment.id}
            </h2>
            <p className="text-gray-700"><span className="font-medium">To:</span> {shipment.receiverName}</p>
            <p className="text-gray-700"><span className="font-medium">Weight:</span> {shipment.weight} kg</p>
            <p className="text-gray-700"><span className="font-medium">Type:</span> {shipment.type}</p>
            <p className="text-gray-700"><span className="font-medium">Status:</span> <span className="capitalize">{shipment.status}</span></p>
            <p className="text-gray-700"><span className="font-medium">Sending branch:</span> {shipment.sendOffice.name}</p>
            <p className="text-gray-700"><span className="font-medium">Receiving branch:</span> {shipment.receiveOffice.name}</p>
          </div>
        ))}
      </div>
    </div>
  );
}

export default {
  title: 'Basic/ShipmentSelector',
  component: ShipmentSelector,
};

export const NothingSelected = {
  render: () => <ShipmentsLayout />,
};

export const WithSelection = {
  render: () => <ShipmentsLayout initialIndex={0} />,
};

export const EmptyList = {
  render: () => (
    <div className="max-w-6xl mx-auto px-4 py-10">
      <h1 className="text-3xl font-bold text-gray-800 mb-6">My Shipments</h1>
      <ShipmentSelector shipments={[]} selectedShipment={null} setSelectedShipment={() => {}} />
      <p className="text-center text-gray-400 mt-10">No shipments found.</p>
    </div>
  ),
};