import { Categorie } from "./categorie";

export interface Livre {
  id: number;
  titre: string;
  dateSortie: string;
  langue: string;
  photoCouverture?: string;

  categories?: Categorie[];
  disponible?: boolean;
}