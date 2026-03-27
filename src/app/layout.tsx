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

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body className={`flex flex-col h-dvh ${poppins.variable} ${inter.variable} antialiased font-body text-body bg-off-white text-black`}>
        <Navbar />
        {children}
        <Footer />
      </body>
    </html>
  );
}
