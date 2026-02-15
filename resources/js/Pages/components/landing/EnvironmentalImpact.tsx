import { motion, useInView } from "framer-motion";
import { useRef } from "react";
import { Leaf, Wind, TreePine } from "lucide-react";
import useAnimatedCounter from "@/hooks/useAnimatedCounter";

const impacts = [
  { icon: Leaf, value: 15800, suffix: " tons", label: "Waste Recycled", color: "text-primary" },
  { icon: Wind, value: 8500, suffix: " tons", label: "CO₂ Reduced", color: "text-eco-olive" },
  { icon: TreePine, value: 42000, suffix: "+", label: "Trees Saved Equivalent", color: "text-eco-leaf" },
];

const EnvironmentalImpact = () => {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <section id="impact" className="py-20 lg:py-32 bg-gradient-forest relative overflow-hidden" ref={ref}>
      {/* Decorative */}
      <div className="absolute inset-0 opacity-10">
        <motion.div animate={{ y: [-20, 20, -20] }} transition={{ duration: 10, repeat: Infinity }} className="absolute top-20 left-[10%]">
          <Leaf className="h-24 w-24 text-primary-foreground" />
        </motion.div>
        <motion.div animate={{ y: [15, -15, 15] }} transition={{ duration: 8, repeat: Infinity }} className="absolute bottom-20 right-[15%]">
          <TreePine className="h-20 w-20 text-primary-foreground" />
        </motion.div>
      </div>

      <div className="container mx-auto px-4 lg:px-8 relative z-10">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={isInView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-center mb-16"
        >
          <span className="inline-flex items-center gap-2 bg-primary-foreground/10 text-primary-foreground px-4 py-2 rounded-full text-sm font-medium mb-4">
            🌱 Our Impact
          </span>
          <h2 className="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-primary-foreground mb-4">
            Making a Real Difference
          </h2>
          <p className="text-lg text-primary-foreground/80 max-w-2xl mx-auto">
            Every transaction on Green Bridge contributes to a cleaner, more
            sustainable world.
          </p>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-8">
          {impacts.map((item, i) => (
            <motion.div
              key={item.label}
              initial={{ opacity: 0, scale: 0.8 }}
              animate={isInView ? { opacity: 1, scale: 1 } : {}}
              transition={{ delay: i * 0.2, duration: 0.5 }}
              className="text-center"
            >
              <item.icon className="h-14 w-14 text-primary-foreground mx-auto mb-4" />
              <CounterDisplay value={item.value} suffix={item.suffix} animate={isInView} />
              <p className="text-primary-foreground/80 text-lg">{item.label}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

const CounterDisplay = ({ value, suffix, animate }: { value: number; suffix: string; animate: boolean }) => {
  const count = useAnimatedCounter(animate ? value : 0, 2500);
  return (
    <p className="text-4xl md:text-5xl font-bold text-primary-foreground mb-2">
      {count.toLocaleString()}{suffix}
    </p>
  );
};

export default EnvironmentalImpact;
