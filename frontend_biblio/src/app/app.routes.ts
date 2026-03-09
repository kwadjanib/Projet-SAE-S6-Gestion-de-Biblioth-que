import { Routes } from '@angular/router';
import { Home } from './components/home/home';
import { LivresList } from './components/livres-list/livres-list';

export const routes: Routes = [

{
path: '',
component: Home
},

{
path: 'livres',
component: LivresList
}

];