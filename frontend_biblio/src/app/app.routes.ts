import { Routes } from '@angular/router';
import { Home } from './components/home/home';
import { LivresList } from './components/livres-list/livres-list';
import { LivreDetail } from './components/livre-detail/livre-detail';
import { Login } from './components/login/login';
import { MonProfil } from './components/mon-profil/mon-profil';
import { MesEmprunts } from './components/mes-emprunts/mes-emprunts';
import { MesReservations } from './components/mes-reservations/mes-reservations';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [

  {
    path: '',
    component: Home
  },

  {
    path: 'livres/:id',
    component: LivreDetail
  },

  {
    path: 'livres',
    component: LivresList,
    pathMatch: 'full'
  },

  {
    path: 'login',
    component: Login
  },

  {
    path: 'mon-profil',
    component: MonProfil,
    canActivate: [authGuard]
  },

  {
    path: 'mes-emprunts',
    component: MesEmprunts,
    canActivate: [authGuard]
  },

  {
    path: 'mes-reservations',
    component: MesReservations,
    canActivate: [authGuard]
  }

];
