"use client";

import type { ReactNode } from "react";
import { motion } from "framer-motion";

type AnimateOnScrollProps = { children: ReactNode; className?: string; delay?: number };

/** Reveal ringan yang menghormati preferensi reduced motion. */
export default function AnimateOnScroll({
  children,
  className,
  delay = 0,
}: AnimateOnScrollProps) {
  return (
    <motion.div
      className={className}
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, amount: 0.15 }}
      transition={{ duration: 0.45, delay }}
    >
      {children}
    </motion.div>
  );
}
