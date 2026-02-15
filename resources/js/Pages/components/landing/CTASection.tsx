import { motion, useInView } from "framer-motion";
import { useRef, useState } from "react";
import { ArrowRight, Mail, Phone, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

const CTASection = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });
  const [email, setEmail] = useState("");

  return (
    <section id="contact" className="py-20 lg:py-32 bg-gradient-forest relative overflow-hidden" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8 relative z-10">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            animate={isInView ? { opacity: 1, x: 0 } : {}}
            transition={{ duration: 0.6 }}
          >
            <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-primary-foreground mb-6">
              Ready to Build a Greener Future?
            </h2>
            <p className="text-lg text-primary-foreground/80 mb-8 leading-relaxed">
              Join the circular economy revolution. Request a demo today and see how
              Green Bridge can transform your waste into wealth.
            </p>

            <div className="space-y-4">
              <div className="flex items-center gap-3 text-primary-foreground/80">
                <Mail className="h-5 w-5" />
                <span>hello@greenbridge.io</span>
              </div>
              <div className="flex items-center gap-3 text-primary-foreground/80">
                <Phone className="h-5 w-5" />
                <span>+1 (555) 123-4567</span>
              </div>
              <div className="flex items-center gap-3 text-primary-foreground/80">
                <MapPin className="h-5 w-5" />
                <span>San Francisco, CA</span>
              </div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 30 }}
            animate={isInView ? { opacity: 1, x: 0 } : {}}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="bg-primary-foreground/10 backdrop-blur-md rounded-3xl p-8 border border-primary-foreground/20"
          >
            <h3 className="text-2xl font-display font-bold text-primary-foreground mb-6">
              Request a Demo
            </h3>
            <form className="space-y-4" onSubmit={(e) => e.preventDefault()}>
              <Input
                placeholder="Your Name"
                className="bg-primary-foreground/10 border-primary-foreground/20 text-primary-foreground placeholder:text-primary-foreground/50 rounded-xl"
              />
              <Input
                type="email"
                placeholder="Work Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="bg-primary-foreground/10 border-primary-foreground/20 text-primary-foreground placeholder:text-primary-foreground/50 rounded-xl"
              />
              <Input
                placeholder="Company Name"
                className="bg-primary-foreground/10 border-primary-foreground/20 text-primary-foreground placeholder:text-primary-foreground/50 rounded-xl"
              />
              <select className="w-full bg-primary-foreground/10 border border-primary-foreground/20 text-primary-foreground rounded-xl px-3 py-2 text-sm">
                <option value="" className="text-foreground">I am a...</option>
                <option value="generator" className="text-foreground">Waste Generator</option>
                <option value="supplier" className="text-foreground">Supplier / Recycler</option>
                <option value="factory" className="text-foreground">Factory / Manufacturer</option>
              </select>
              <Button
                type="submit"
                className="w-full bg-primary-foreground text-primary hover:bg-primary-foreground/90 rounded-full font-semibold text-base py-3"
              >
                Request Demo
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
            </form>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default CTASection;
