import { ImageOff } from "lucide-react";

export default function MediaPlaceholder({ label }: { label: string }) {
  return (
    <div className="flex size-full flex-col items-center justify-center gap-3 bg-navy text-center text-white/70">
      <ImageOff size={34} strokeWidth={1.5} aria-hidden="true" />
      <span className="px-4 text-xs font-semibold">{label}</span>
    </div>
  );
}
