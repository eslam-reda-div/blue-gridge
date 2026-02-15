import { motion, useInView } from "framer-motion";
import { useRef } from "react";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

const faqs = [
  {
    q: "What is Green Bridge and how does it work?",
    a: "Green Bridge is a B2B SaaS platform that creates a circular economy by connecting waste generators (hotels, malls, parks, barber shops), recycling suppliers, and factories. The platform automates the entire process from waste collection to material recycling to product manufacturing.",
  },
  {
    q: "Who can use Green Bridge?",
    a: "Any business that generates waste, processes/recycles waste materials, or manufactures products using raw materials. This includes hotels, malls, parks, barber shops, recycling companies, material processors, and manufacturing facilities.",
  },
  {
    q: "How does the matching system work?",
    a: "Our AI-powered matching engine connects waste generators with the most suitable suppliers based on waste type, location, volume, and pricing. Suppliers are then matched with factories based on material quality and requirements.",
  },
  {
    q: "Is my data secure on the platform?",
    a: "Absolutely. We use enterprise-grade encryption, comply with industry regulations, and follow strict data privacy practices. All transactions and sensitive information are protected with bank-level security.",
  },
  {
    q: "How long does onboarding take?",
    a: "Most businesses are fully onboarded within 24-48 hours. Our team guides you through the setup process, helps configure your dashboard, and ensures you're ready to start listing or sourcing materials.",
  },
  {
    q: "What payment methods are supported?",
    a: "We support bank transfers, credit/debit cards, and various digital payment methods. All payments are processed securely with automated invoicing and reconciliation.",
  },
  {
    q: "Can I try Green Bridge before committing?",
    a: "Yes! All plans come with a 14-day free trial with full access to all features. No credit card required to start.",
  },
];

const FAQSection = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section className="py-20 lg:py-32 bg-card" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8 max-w-3xl">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            ❓ FAQ
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            Frequently Asked Questions
          </h2>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ delay: 0.2 }}
        >
          <Accordion type="single" collapsible className="space-y-3">
            {faqs.map((faq, i) => (
              <AccordionItem
                key={i}
                value={`item-${i}`}
                className="bg-background border border-border rounded-xl px-6 data-[state=open]:shadow-earth transition-shadow"
              >
                <AccordionTrigger className="text-left font-semibold text-foreground hover:no-underline">
                  {faq.q}
                </AccordionTrigger>
                <AccordionContent className="text-muted-foreground leading-relaxed">
                  {faq.a}
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </motion.div>
      </div>
    </section>
  );
};

export default FAQSection;
