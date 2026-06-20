"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import { CURRENT_YEAR } from "@/util/constants";
import { fetchTimesheetData } from "@/util/fetch";
import { sumOutstandingBalance } from "@/util/timesheet";
import LoginForm from "./LoginForm";
import AccountSummary from "./AccountSummary";
import ClientPortalNavbar, { ClientPortalNavbarProps } from "./ClientPortalNavbar";
import { CircleDollarSignIcon } from "lucide-react";

export default function ClientPortalSPA() {
    const [year, setYear] = useState(CURRENT_YEAR);
    const [client, setClient] = useState("");
    const [password, setPassword] = useState("");
    const [timesheetData, setTimesheetData] = useState<TimesheetResponse | null>(null);
    const [currentYearDue, setCurrentYearDue] = useState<number | null>(null);

    useEffect(() => {
        if ( ! client || ! password || ! year ) {
            return;
        }

        let cancelled = false;

        fetchTimesheetData(client, password, year).then((data: TimesheetResponse) => {
            if ( cancelled ) {
                return;
            }
            setTimesheetData(data);
            if ( year === CURRENT_YEAR ) {
                setCurrentYearDue(sumOutstandingBalance(data.rows));
            }
        }).catch(error => {
            if ( ! cancelled ) {
                window.alert(error.message ?? "An unknown error occurred");
            }
        });

        return () => {
            cancelled = true;
        };
    }, [client, password, year]);

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

    const handleLoginSuccess = useCallback((client: string, password: string, data: TimesheetResponse) => {
        setClient(client);
        setPassword(password);
        setTimesheetData(data);
        if ( year === CURRENT_YEAR ) {
            setCurrentYearDue(sumOutstandingBalance(data.rows));
        }
    }, [setClient, setPassword, setTimesheetData, year]);

    return ( ! timesheetData ) ?
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-center bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center">
                <LoginForm year={year} onSuccess={handleLoginSuccess} />
            </main>
        ) :
        (
            <main className="component-ClientPortalSPA flex-1 flex flex-col align-center justify-start bg-primary-lightest">
                <ClientPortalNavbar
                    clientName={timesheetData.client.name}
                    moreNavLinks={clientPortalNavbarLinks}
                    currentYearDue={currentYearDue}
                />
                <AccountSummary year={year} setYear={setYear} data={timesheetData} />
            </main>
        );
}
