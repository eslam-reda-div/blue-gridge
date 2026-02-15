import { motion, useInView } from "framer-motion";
import { useRef, useState } from "react";
import { Building2, Recycle, Factory, Check, ArrowRight } from "lucide-react";
import { Button } from "@/components/ui/button";

const stakeholders = [
  {
    id: "generators",
    icon: Building2,
    emoji: "🏨",
    title: "For Waste Generators",
    subtitle: "Hotels, Malls, Parks, Barber Shops",
    description:
      "Turn your waste into a revenue stream. List waste materials, schedule automated pickups, and track every transaction in real-time.",
    features: [
      "Turn waste into revenue instantly",
      "Easy waste listing with smart categorization",
      "Automated pickup scheduling",
      "Real-time tracking & notifications",
      "Revenue analytics dashboard",
      "Compliance documentation",
    ],
    cta: "Start Earning from Waste",
  },
  {
    id: "suppliers",
    icon: Recycle,
    emoji: "♻️",
    title: "For Suppliers & Recyclers",
    subtitle: "Recycling Companies & Material Processors",
    description:
      "Find reliable waste sources, manage recycling operations efficiently, and sell processed materials to verified factories.",
    features: [
      "Access reliable waste sources nearby",
      "Manage recycling operations end-to-end",
      "Sell processed materials to verified factories",
      "Smart pricing & bidding tools",
      "Supply chain visibility",
      "Quality certification management",
    ],
    cta: "Grow Your Supply Chain",
  },
  {
    id: "factories",
    icon: Factory,
    emoji: "🏭",
    title: "For Factories",
    subtitle: "Manufacturing & Production Facilities",
    description:
      "Source affordable, high-quality recycled raw materials. Reduce your environmental footprint while cutting material costs.",
    features: [
      "Source affordable recycled materials",
      "Reduce environmental footprint",
      "Verified supply chain & quality assurance",
      "Bulk purchasing & contracts",
      "Cost savings analytics",
      "Sustainability reporting",
    ],
    cta: "Source Recycled Materials",
  },
];

const StakeholderSpotlight = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });
  const [active, setActive] = useState(0);

  const current = stakeholders[active];

  return (
    <section id="stakeholders" className="py-20 lg:py-32 bg-card" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            🏢 Built for Everyone
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            One Platform, Three Stakeholders
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Whether you generate waste, recycle it, or manufacture with it — Green
            Bridge has the tools you need.
          </p>
        </motion.div>

        {/* Tabs */}
        <div className="flex flex-wrap justify-center gap-3 mb-12">
          {stakeholders.map((s, i) => (
            <button
              key={s.id}
              onClick={() => setActive(i)}
              className={`flex items-center gap-2 px-6 py-3 rounded-full font-medium text-sm transition-all duration-300 ${
                active === i
                  ? "bg-primary text-primary-foreground shadow-green"
                  : "bg-muted text-muted-foreground hover:bg-primary/10 hover:text-primary"
              }`}
            >
              <span className="text-lg">{s.emoji}</span>
              {s.title.replace("For ", "")}
            </button>
          ))}
        </div>

        {/* Content */}
        <motion.div
          key={current.id}
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4 }}
          className="grid lg:grid-cols-2 gap-12 items-center"
        >
          <div>
            <div className="flex items-center gap-3 mb-4">
              <span className="text-4xl">{current.emoji}</span>
              <div>
                <h3 className="text-2xl font-display font-bold text-foreground">
                  {current.title}
                </h3>
                <p className="text-sm text-muted-foreground">{current.subtitle}</p>
              </div>
            </div>
            <p className="text-muted-foreground leading-relaxed mb-8 text-lg">
              {current.description}
            </p>
            <Button className="bg-primary hover:bg-eco-forest-light text-primary-foreground rounded-full px-8 font-semibold">
              {current.cta}
              <ArrowRight className="ml-2 h-4 w-4" />
            </Button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {current.features.map((feature, i) => (
              <motion.div
                key={feature}
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: i * 0.08 }}
                className="flex items-start gap-3 p-4 rounded-xl bg-background border border-border shadow-sm hover:shadow-earth transition-shadow"
              >
                <Check className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <span className="text-sm font-medium text-foreground">{feature}</span>
              </motion.div>
            ))}
          </div>
        </motion.div>
      </div>
    </section>
  );
};

export default StakeholderSpotlight;
