import React from 'react';
import { NavbarSimple } from './Navbar';

const Layout = ({ children }) => {
  return (
    <div>
      <NavbarSimple />
      {children}
    </div>
  );
};

export default Layout;
