import Footer from '@/components/ui/Footer';
import Navbar from '@/components/ui/Navbar';
import { InfoIcon, MoveLeftIcon } from 'lucide-react';
import { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
    title: "Page not found | Purple Turtle Creative",
    description: "Oops! It seems you stumbled upon some content which has since been removed.",
    icons: {
        icon: "/favicon.png",
    }
};

export default function NotFound() {
    return (
        <>
            <Navbar />
                <main className="flex-1 flex flex-col align-center justify-center pt-26 bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center">
                    <div className="content-section sm:mb-[20dvh]">
                        <h1 className="font-heading my-5 text-shadow-lg text-shadow-primary-dark/30 text-[#9eaafb]">Error <span className="text-white">Not Found</span></h1>
                        <p className="text-lg sm:text-xl">Oops! It seems you stumbled upon some content which has since been removed.</p>
                        <p className="bg-primary-lightest text-primary-dark text-sm px-3 py-2 rounded-lg mt-3 w-fit mx-auto"><InfoIcon className="inline-block relative bottom-[0.1em]" width="1.3em" height="1.3em" />&ensp;In early 2026, we closed some projects, shifted priorities, and redesigned our website.</p>
                        <Link href="/" className="button button--primary-dark w-fit mx-auto my-10"><MoveLeftIcon />Return home</Link>
                    </div>
                </main>
            <Footer />
        </>
    )
}
