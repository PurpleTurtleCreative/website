import type { Metadata } from "next";
import { Poppins, Inter } from "next/font/google";
import "./globals.css";
import Footer from "@/components/ui/Footer";
import Navbar from "@/components/ui/Navbar";

const poppins = Poppins({
  variable: "--font-poppins",
  subsets: ["latin"],
  weight: ["800"],
});

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
  weight: ["400"],
});

export const metadata: Metadata = {
  title: "Digital Strategy without the Drama | Purple Turtle Creative",
  description: "Technology solutions that boost business efficiency, from internal processes to user-facing conversion engines. Check out our services to see how your business can thrive in the digital age.",
  icons: {
    icon: "/favicon.png",
  }
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body className={`${poppins.variable} ${inter.variable} antialiased font-body text-body bg-off-white text-black`}>
        <Navbar />
        {children}
        <Footer />
      </body>
    </html>
  );
}
