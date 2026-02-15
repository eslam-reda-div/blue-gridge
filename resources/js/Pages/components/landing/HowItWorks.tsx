import { motion } from "framer-motion";
import { useInView } from "framer-motion";
import { useRef } from "react";
import { Trash2, Recycle, Factory, ArrowRight } from "lucide-react";

const steps = [
  {
    icon: Trash2,
    emoji: "📦",
    title: "Collect",
    description:
      "Waste generators — hotels, malls, barber shops, parks — list their waste materials on the platform for suppliers to purchase.",
    color: "bg-secondary/10 text-secondary border-secondary/20",
  },
  {
    icon: Recycle,
    emoji: "♻️",
    title: "Recycle",
    description:
      "Suppliers purchase the waste, transport it, and process it through recycling to produce high-quality raw materials ready for manufacturing.",
    color: "bg-primary/10 text-primary border-primary/20",
  },
  {
    icon: Factory,
    emoji: "🏭",
    title: "Manufacture",
    description:
      "Factories buy the recycled raw materials from suppliers at competitive prices to create new products — closing the loop.",
    color: "bg-accent/10 text-accent border-accent/20",
  },
];

const HowItWorks = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section id="how-it-works" className="py-20 lg:py-32 bg-background" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            🔄 The Circular Flow
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            How It Works
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Three simple steps that transform waste into valuable products — creating
            a sustainable, profitable cycle for everyone involved.
          </p>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-8 lg:gap-4 items-start relative">
          {steps.map((step, index) => (
            <motion.div
              key={step.title}
              initial={{ opacity: 0, y: 40 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.6, delay: index * 0.2 }}
              className="relative flex flex-col items-center text-center"
            >
              {/* Step number */}
              <div className="w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-lg mb-6">
                {index + 1}
              </div>

              {/* Icon card */}
              <div
                className={`w-24 h-24 rounded-3xl ${step.color} border flex items-center justify-center mb-6 shadow-earth transition-transform duration-300 hover:scale-110`}
              >
                <span className="text-4xl">{step.emoji}</span>
              </div>

              <h3 className="text-2xl font-display font-bold text-foreground mb-3">
                {step.title}
              </h3>
              <p className="text-muted-foreground leading-relaxed max-w-xs">
                {step.description}
              </p>

              {/* Arrow connector (hidden on last) */}
              {index < steps.length - 1 && (
                <div className="hidden md:block absolute top-24 -right-4 lg:right-[-2rem]">
                  <ArrowRight className="h-8 w-8 text-primary/30" />
                </div>
              )}
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default HowItWorks;
