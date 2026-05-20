"use client";

import { useEffect, useState } from "react";
import { BUSINESS_TIME_ZONE } from "@/util/constants";

export default function Clock() {
    const [currentTime, setCurrentTime] = useState(new Date());
    const [mounted, setMounted] = useState(false);

    useEffect(() => {
        setMounted(true);
        const tick = () => {
            setCurrentTime(new Date());
        };

        tick();
        const intervalId = setInterval(tick, 60000);

        return () => clearInterval(intervalId);
    }, []);

    const zoneParts = new Intl.DateTimeFormat("en-US", {
        timeZone: BUSINESS_TIME_ZONE,
        hour12: true,
        hour: "numeric",
        minute: "2-digit",
        timeZoneName: "short",
    }).formatToParts(currentTime);

    const hour = zoneParts.find((part) => part.type === "hour")?.value ?? "--";
    const minute = zoneParts.find((part) => part.type === "minute")?.value ?? "--";
    const dayPeriod = zoneParts.find((part) => part.type === "dayPeriod")?.value.toLowerCase() ?? "";
    const timeZoneName = zoneParts.find((part) => part.type === "timeZoneName")?.value ?? "";

    return (
        <span className="component-Clock">
            {mounted ? hour : "--"}
            <span className="animate-blink">:</span>
            {mounted ? minute : "--"}
            <span className="text-xs">{` ${mounted ? dayPeriod : "--"} ${mounted ? timeZoneName : "---"}`}</span>
        </span>
    );
}
