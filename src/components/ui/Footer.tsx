import { FlameIcon } from "lucide-react";

export default function Footer() {
    return (
        <footer className="component-Footer w-full">
            <div className="content-section flex flex-wrap items-center justify-between gap-y-3 py-5 text-sm">
                <p>{`©${new Date().getFullYear()} Purple Turtle Creative, LLC. All rights reserved.`}</p>
                <p>Made with <FlameIcon fill="currentColor" className="inline-block text-orange-500 relative bottom-[0.15em]" aria-label="passion" width="1.3em" height="1.3em" /> in New York, USA.</p>
            </div>
        </footer>
    );
}
