import { BuildingIcon, ClockIcon, MailIcon } from "lucide-react";
import Clock from "../client/Clock";

export default function Footer() {
    return (
        <footer className="component-Footer w-full">
            <div className="content-section flex flex-wrap items-center justify-between gap-y-3 py-5 text-sm">
                <p>{`©${new Date().getFullYear()} Purple Turtle Creative, LLC. All rights reserved.`}</p>
                <ul className="list-none flex flex-wrap gap-x-5">
                    <li><BuildingIcon className="inline-block relative bottom-[0.1em]" aria-label="headquarters" width="1.3em" height="1.3em" /> New York, USA</li>
                    <li><ClockIcon className="inline-block relative bottom-[0.1em]" aria-label="business local time" width="1.3em" height="1.3em" /> <Clock /></li>
                    <li><MailIcon className="inline-block relative bottom-[0.1em]" aria-label="email contact" width="1.3em" height="1.3em" /> <a href="mailto:michelle@purpleturtlecreative.com">Michelle Blanchette</a></li>
                </ul>
            </div>
        </footer>
    );
}
