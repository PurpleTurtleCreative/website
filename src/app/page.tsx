import Intro from "@/components/sections/Intro";
import RequestQuote from "@/components/sections/RequestQuote";
import Services from "@/components/sections/Services";
import Tech from "@/components/sections/Tech";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Digital Strategy without the Drama | Purple Turtle Creative",
  description: "Technology solutions that boost business efficiency, from internal processes to user-facing conversion engines. Check out our services to see how your business can thrive in the digital age.",
  icons: {
    icon: "/favicon.png",
  }
};

export default function Home() {
  return (
    <main>
      <Intro />
      <Services />
      <Tech />
      <RequestQuote />
    </main>
  );
}
