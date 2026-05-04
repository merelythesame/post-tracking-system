/** @module PrivacyPolicyModal */
/**
 * Modal overlay displaying the Privacy Policy content.
 * Rendered on top of the cookie consent banner when the user
 * clicks the "Privacy Policy" link.
 *
 * @component
 * @param {Object} props
 * @param {function(): void} props.onClose - Callback to close the modal
 * @returns {JSX.Element}
 */

export default function PrivacyPolicyModal({ onClose }) {
  return (
    <div style={{
      position: "fixed", inset: 0, background: "rgba(0,0,0,0.5)",
      display: "flex", alignItems: "center", justifyContent: "center",
      zIndex: 99999
    }}>
      <div style={{
        background: "#fff", borderRadius: "16px", padding: "36px",
        maxWidth: "560px", width: "90%", position: "relative",
        maxHeight: "80vh", overflowY: "auto",
        boxShadow: "0 8px 32px rgba(0,0,0,0.2)"
      }}>
        <button onClick={onClose} style={{
          position: "absolute", top: "16px", right: "20px",
          background: "none", border: "none", fontSize: "22px",
          cursor: "pointer", color: "#222"
        }}>✕</button>

        <h2 style={{ marginBottom: "16px" }}>Privacy Policy</h2>

        <h3>1. Data We Collect</h3>
        <p>Email address, name, password (hashed), shipment data, session cookies, and support ticket messages.</p>

        <h3>2. Purpose of Processing</h3>
        <p>Authentication, shipment tracking, and customer support.</p>

        <h3>3. Cookies</h3>
        <p>We use session cookies for authentication purposes only. No advertising or tracking cookies are used.</p>

        <h3>4. Your Rights (GDPR)</h3>
        <p>You have the right to access, correct, or delete your data. Contact us via the Support section of the application.</p>

        <h3>5. Data Retention</h3>
        <p>Data is retained while your account is active. Deleted accounts are purged within 30 days.</p>
      </div>
    </div>
  );
}