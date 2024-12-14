export type SectionItem = {
  id: string;
  title: string;
  description: string;
  isVisible?: boolean;
  toggleValue?: boolean;
  sideButton?: {
    text: string;
    onClick: () => void;
  };
  copyLink?: string;
};
