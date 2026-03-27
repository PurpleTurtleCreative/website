import Intro from "@/components/sections/Intro";
import RequestQuote from "@/components/sections/RequestQuote";
import Services from "@/components/sections/Services";
import Tech from "@/components/sections/Tech";

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
