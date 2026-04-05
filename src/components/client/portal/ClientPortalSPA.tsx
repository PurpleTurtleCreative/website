"use client";

import { useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import LoginForm from "./LoginForm";
import AccountSummary from "./AccountSummary";

export default function ClientPortalSPA() {
    const [timesheetData, setTimesheetData] = useState<TimesheetResponse | null>(null);

    return (
        <div className="component-ClientPortalSPA">
            {
                ( ! timesheetData ) ? (
                    <LoginForm onSubmit={setTimesheetData} />
                ) : (
                    <AccountSummary data={timesheetData} />
                )
            }
        </div>
    );
}
