import ClientPortalSPA from "@/components/client/portal/ClientPortalSPA";
import { Metadata } from "next";

export const metadata: Metadata = {
    title: "Client Portal",
    description: "Access your account summary, payments, and outstanding balance.",
};

export default function PortalPage() {
    return (
        <main>
            <ClientPortalSPA />
        </main>
    );
}
