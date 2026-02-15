import { motion, useInView } from "framer-motion";
import { useRef } from "react";
import { BarChart3, TrendingUp, Leaf, DollarSign, Package, Activity } from "lucide-react";
import useAnimatedCounter from "@/hooks/useAnimatedCounter";

const stats = [
  { label: "Revenue Tracked", value: 2400000, prefix: "$", suffix: "+", icon: DollarSign },
  { label: "Waste Diverted (tons)", value: 15800, prefix: "", suffix: "", icon: Package },
  { label: "Active Stakeholders", value: 1240, prefix: "", suffix: "+", icon: Activity },
  { label: "CO₂ Reduced (tons)", value: 8500, prefix: "", suffix: "", icon: Leaf },
];

const dashboardFeatures = [
  { title: "Revenue & Earnings Tracking", desc: "Real-time financial performance for all stakeholders" },
  { title: "Waste Volume Trends", desc: "Track collection and recycling volumes over time" },
  { title: "Supply Chain Visibility", desc: "End-to-end tracking from waste source to final product" },
  { title: "Environmental Impact Metrics", desc: "CO₂ saved, waste diverted, trees saved equivalent" },
];

const DashboardPreview = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section className="py-20 lg:py-32 bg-background" ref={ref}>
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
            📊 Smart Analytics
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-foreground mb-4">
            Data-Driven Decisions
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Every stakeholder gets a personalized analytics dashboard with actionable
            insights to optimize performance.
          </p>
        </motion.div>

        {/* Stats counters */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
          {stats.map((stat, i) => (
            <motion.div
              key={stat.label}
              initial={{ opacity: 0, y: 30 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ delay: i * 0.15 }}
              className="bg-card rounded-2xl p-6 border border-border shadow-earth text-center"
            >
              <stat.icon className="h-8 w-8 text-primary mx-auto mb-3" />
              <AnimatedStat
                value={stat.value}
                prefix={stat.prefix}
                suffix={stat.suffix}
                animate={isInView}
              />
              <p className="text-sm text-muted-foreground mt-1">{stat.label}</p>
            </motion.div>
          ))}
        </div>

        {/* Dashboard mockup */}
        <motion.div
          initial={{ opacity: 0, y: 40 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.8, delay: 0.3 }}
          className="relative rounded-3xl overflow-hidden border border-border shadow-earth bg-card p-6 lg:p-8"
        >
          {/* Mock dashboard UI */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {/* Sidebar mock */}
            <div className="lg:col-span-1 bg-muted rounded-2xl p-4 space-y-3">
              <div className="flex items-center gap-2 mb-4">
                <BarChart3 className="h-5 w-5 text-primary" />
                <span className="font-semibold text-foreground text-sm">Dashboard</span>
              </div>
              {dashboardFeatures.map((f) => (
                <div key={f.title} className="p-3 rounded-xl bg-background border border-border">
                  <p className="text-sm font-medium text-foreground">{f.title}</p>
                  <p className="text-xs text-muted-foreground mt-1">{f.desc}</p>
                </div>
              ))}
            </div>
            {/* Chart area mock */}
            <div className="lg:col-span-2 space-y-4">
              <div className="bg-muted rounded-2xl p-6 h-48 flex items-end justify-between gap-2">
                {[40, 65, 45, 80, 55, 90, 70, 85, 60, 95, 75, 88].map((h, i) => (
                  <motion.div
                    key={i}
                    initial={{ height: 0 }}
                    animate={isInView ? { height: `${h}%` } : {}}
                    transition={{ delay: 0.5 + i * 0.05, duration: 0.5 }}
                    className="flex-1 bg-primary/70 rounded-t-lg"
                  />
                ))}
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="bg-muted rounded-2xl p-4 flex items-center gap-3">
                  <TrendingUp className="h-8 w-8 text-eco-leaf" />
                  <div>
                    <p className="text-lg font-bold text-foreground">+34%</p>
                    <p className="text-xs text-muted-foreground">Revenue Growth</p>
                  </div>
                </div>
                <div className="bg-muted rounded-2xl p-4 flex items-center gap-3">
                  <Leaf className="h-8 w-8 text-primary" />
                  <div>
                    <p className="text-lg font-bold text-foreground">-28%</p>
                    <p className="text-xs text-muted-foreground">Carbon Footprint</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  );
};

const AnimatedStat = ({
  value,
  prefix,
  suffix,
  animate,
}: {
  value: number;
  prefix: string;
  suffix: string;
  animate: boolean;
}) => {
  const count = useAnimatedCounter(animate ? value : 0, 2000);
  return (
    <p className="text-2xl lg:text-3xl font-bold text-foreground">
      {prefix}
      {count.toLocaleString()}
      {suffix}
    </p>
  );
};

export default DashboardPreview;
