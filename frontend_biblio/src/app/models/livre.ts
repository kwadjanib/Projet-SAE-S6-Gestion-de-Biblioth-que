import { Categorie } from "./categorie";
import { Auteur } from "./auteur";

export interface Livre {
  id: number;
  titre: string;
  dateSortie: string;
  langue: string;
  photoCouverture?: string;

  categories?: Categorie[];
  auteurs?: Auteur[];
  disponible?: boolean;
}
