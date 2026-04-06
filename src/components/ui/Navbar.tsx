import { CircleDollarSignIcon, CircleUserIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";

export default function Navbar() {

    const navLinks = [
        {
            href: "/portal",
            icon: <CircleUserIcon width="1.5em" height="1.5em" />,
            label: "Client Portal",
        },
        {
            href: "https://buy.stripe.com/7sY8wP86bbz3cWmfyG73G03",
            icon: <CircleDollarSignIcon width="1.5em" height="1.5em" />,
            label: "Submit Payment",
        }
    ];

    return (
        <header className="component-Navbar w-full absolute top-0 z-999">
            <div className="content-section flex items-start sm:items-center justify-between gap-3">
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
                                <Link href={link.href} className="button button--primary-dark text-sm">
                                    {link.icon}<span>{link.label}</span>
                                </Link>
                            </li>
                        ))}
                    </ul>
                </nav>
            </div>
        </header>
    );
}
