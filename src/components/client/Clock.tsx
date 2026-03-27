"use client";

import { useEffect, useState } from "react";

export default function Clock() {
    const [currentTime, setCurrentTime] = useState(new Date());

    useEffect(() => {
        const tick = () => {
            setCurrentTime(new Date());
        };

        tick();
        const intervalId = setInterval(tick, 60000);

        return () => clearInterval(intervalId);
    }, []);

    const zoneParts = new Intl.DateTimeFormat("en-US", {
        timeZone: "America/New_York",
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
            {hour}
            <span className="animate-blink">:</span>
            {minute}
            <span className="text-xs"> {dayPeriod} {timeZoneName}</span>
        </span>
    );
}
