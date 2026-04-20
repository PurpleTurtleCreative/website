"use client";

import { useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import LoginForm from "./LoginForm";
import AccountSummary from "./AccountSummary";
import ClientPortalNavbar from "./ClientPortalNavbar";

export default function ClientPortalSPA() {
    const [timesheetData, setTimesheetData] = useState<TimesheetResponse | null>(null);

    return ( ! timesheetData ) ?
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-center bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center">
                <LoginForm onSubmit={setTimesheetData} />
            </main>
        ) :
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-center bg-primary-lightest">
                <ClientPortalNavbar />
                <AccountSummary data={timesheetData} />
            </main>
        );
}
