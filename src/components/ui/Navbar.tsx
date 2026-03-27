import { CircleDollarSignIcon, MailIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";

export default function Navbar() {

    const navLinks = [
        {
            href: "https://buy.stripe.com/7sY8wP86bbz3cWmfyG73G03",
            icon: <CircleDollarSignIcon width="1.5em" height="1.5em" />,
            label: "Submit Payment",
        },
        {
            href: "mailto:michelle@purpleturtlecreative.com",
            icon: <MailIcon width="1.5em" height="1.5em" />,
            label: "Send Email",
        },
    ];

    return (
        <header className="component-Navbar w-full absolute top-0 z-999">
            <div className="content-section flex items-center justify-between gap-3">
                <Link href="/">
                    <Image
                        src="/images/purpleturtlecreative-logo-horizontal-light.svg"
                        alt="Purple Turtle Creative"
                        width={233}
                        height={54}
                        priority
                        className="drop-shadow drop-shadow-primary-dark/30"
                    />
                </Link>
                <nav>
                    <ul className="flex flex-wrap items-center justify-end gap-1 sm:gap-3">
                        {navLinks.map((link) => (
                            <li key={link.label}>
                                <a href={link.href} className="button text-sm">
                                    {link.icon}<span>{link.label}</span>
                                </a>
                            </li>
                        ))}
                    </ul>
                </nav>
            </div>
        </header>
    );
}
