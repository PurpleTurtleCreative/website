import Footer from "@/components/ui/Footer";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: {
    template: "%s | Purple Turtle Creative",
    default: "Client Portal | Purple Turtle Creative",
  },
  description: "Access your account summary, payments, and outstanding balance.",
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
    <>
      {children}
      <Footer />
    </>
  );
}
