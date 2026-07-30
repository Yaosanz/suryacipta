type SectionHeaderProps = {
  eyebrow: string;
  title: string;
  description?: string;
  align?: "left" | "center";
};

/** Judul section konsisten untuk menjaga hierarki konten. */
export default function SectionHeader({
  eyebrow,
  title,
  description,
  align = "left",
}: SectionHeaderProps) {
  return (
    <div className={align === "center" ? "mx-auto max-w-2xl text-center" : "max-w-2xl"}>
      <p className="eyebrow">{eyebrow}</p>
      <h2 className="display-title mt-3">{title}</h2>
      {description && <p className="mt-5 leading-7 text-muted">{description}</p>}
    </div>
  );
}
