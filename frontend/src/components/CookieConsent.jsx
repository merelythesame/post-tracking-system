import { useState } from "react";
import CookieConsent from "react-cookie-consent";
import PrivacyPolicyModal from "./PrivacyPolicyModal";

/** @module CookieConsentBanner */
/**
 * GDPR-compliant cookie consent banner displayed at the bottom of the page.
 * Uses react-cookie-consent to manage consent state via cookies.
 * Includes a link that opens the PrivacyPolicyModal without leaving the page.
 *
 * @component
 * @returns {JSX.Element}
 */

export default function CookieConsentBanner() {
  const [showPolicy, setShowPolicy] = useState(false);

  return (
    <>
      <CookieConsent
        location="bottom"
        buttonText="Accept All"
        declineButtonText="Decline"
        enableDeclineButton
        cookieName="pts_gdpr_consent"
        style={{ background: "#2B373B" }}
        buttonStyle={{ background: "#4CAF50", color: "#fff" }}
        expires={365}
        onAccept={() => console.log("Cookies accepted")}
        onDecline={() => console.log("Cookies declined")}
      >
        We use cookies to ensure session functionality and improve our service.{" "}
        <span
          onClick={() => setShowPolicy(true)}
          style={{ color: "#fff", textDecoration: "underline", cursor: "pointer" }}
        >
          Privacy Policy
        </span>
      </CookieConsent>

      {showPolicy && <PrivacyPolicyModal onClose={() => setShowPolicy(false)} />}
    </>
  );
}