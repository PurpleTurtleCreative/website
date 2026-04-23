"use client";

import { useMemo, useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import LoginForm from "./LoginForm";
import AccountSummary from "./AccountSummary";
import ClientPortalNavbar, { ClientPortalNavbarProps } from "./ClientPortalNavbar";
import { CircleDollarSignIcon } from "lucide-react";

export default function ClientPortalSPA() {
    const [timesheetData, setTimesheetData] = useState<TimesheetResponse | null>(null);

    const clientPortalNavbarLinks = useMemo(() => {
        const navLinks: ClientPortalNavbarProps["moreNavLinks"] = [];
        if ( timesheetData?.client.submitPaymentUrl ) {
            navLinks.push({
                href: timesheetData.client.submitPaymentUrl,
                icon: <CircleDollarSignIcon width="1.5em" height="1.5em" />,
                label: "Submit Payment",
            });
        }
        return navLinks;
    }, [timesheetData]);

    return ( ! timesheetData ) ?
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-center bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center">
                <LoginForm onSubmit={setTimesheetData} />
            </main>
        ) :
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-center bg-primary-lightest">
                <ClientPortalNavbar clientName={timesheetData.client.name} moreNavLinks={clientPortalNavbarLinks} />
                <AccountSummary data={timesheetData} />
            </main>
        );
}
