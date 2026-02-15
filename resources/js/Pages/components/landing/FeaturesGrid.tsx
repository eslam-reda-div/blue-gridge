import { motion, useInView } from "framer-motion";
import { useRef } from "react";
import {
  Workflow,
  ClipboardList,
  Bell,
  BadgeDollarSign,
  ShieldCheck,
  LayoutDashboard,
  CreditCard,
  BarChart3,
} from "lucide-react";

const features = [
  {
    icon: Workflow,
    title: "Automated Matching",
    desc: "AI-powered matching connects waste generators with the right suppliers and factories automatically.",
  },
  {
    icon: ClipboardList,
    title: "End-to-End Process Management",
    desc: "Manage every step from waste listing to final product delivery in one unified platform.",
  },
  {
    icon: Bell,
    title: "Real-Time Tracking & Notifications",
    desc: "Live updates on pickups, deliveries, and processing status with instant alerts.",
  },
  {
    icon: BadgeDollarSign,
    title: "Smart Pricing & Bidding",
    desc: "Dynamic pricing engine and bidding system ensures fair market value for all parties.",
  },
  {
    icon: ShieldCheck,
    title: "Compliance & Documentation",
    desc: "Automated regulatory compliance tracking and documentation for waste management.",
  },
  {
    icon: LayoutDashboard,
    title: "Multi-Stakeholder Dashboards",
    desc: "Personalized dashboards with role-specific analytics, metrics, and insights.",
  },
  {
    icon: CreditCard,
    title: "Payment Processing & Invoicing",
    desc: "Seamless payment flows, automated invoicing, and financial reconciliation.",
  },
  {
    icon: BarChart3,
    title: "Environmental Impact Reports",
    desc: "Track and share your sustainability impact with detailed environmental reports.",
  },
];

const FeaturesGrid = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section id="features" className="py-20 lg:py-32 bg-card" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            ⚡ Platform Features
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            Everything You Need
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            A complete suite of tools to automate, manage, and optimize the circular
            economy supply chain.
          </p>
        </motion.div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {features.map((f, i) => (
            <motion.div
              key={f.title}
              initial={{ opacity: 0, y: 30 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ delay: i * 0.08, duration: 0.5 }}
              className="group bg-background border border-border rounded-2xl p-6 hover:shadow-earth transition-all duration-300 hover:-translate-y-1"
            >
              <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-primary-foreground transition-colors duration-300">
                <f.icon className="h-6 w-6 text-primary group-hover:text-primary-foreground transition-colors" />
              </div>
              <h3 className="text-lg font-display font-bold text-foreground mb-2">
                {f.title}
              </h3>
              <p className="text-sm text-muted-foreground leading-relaxed">
                {f.desc}
              </p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default FeaturesGrid;
