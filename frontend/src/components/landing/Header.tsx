
// src/components/landing/Header.tsx
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, Search, User, Heart, Crown, LogOut, Settings } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useIsMobile } from '@/hooks/use-mobile';
import { useAuth } from '@/hooks/useAuth';
import {
  getDashboardRedirectPath,
  getFilamentRedirectUrl,
  isFilamentRedirectPath,
} from '@/lib/dashboardRoutes';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const Header: React.FC = () => {
  const [isMenuOpen, setIsMenuOpen] = React.useState(false);
  const [isScrolled, setIsScrolled] = React.useState(false);
  const isMobile = useIsMobile();
  const location = useLocation();
  const { user, isAuthenticated, logout } = useAuth();
  const dashboardPath = getDashboardRedirectPath(user, '/dashboard');

  React.useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  React.useEffect(() => {
    if (!isMobile) setIsMenuOpen(false);
  }, [isMobile]);

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen);

  const isActiveRoute = (path: string) => {
    if (path === '/') return location.pathname === '/';
    return location.pathname.startsWith(path);
  };

  return (
    <header
      className={`fixed top-0 left-0 w-full z-50 transition-all duration-500 ${
        isScrolled 
          ? 'bg-background/95 backdrop-blur-lg shadow-lg border-b border-border/50 py-3' 
          : 'bg-transparent py-6'
      }`}
    >
      <div className="container mx-auto px-6 flex items-center justify-between">
        {/* Logo */}
        <Link to="/" className="flex items-center space-x-2 group">
          <Crown className="w-7 h-7 text-primary group-hover:animate-sparkle" />
          <span className="text-2xl lg:text-3xl font-playfair font-bold heading-elegant">
            Latest Fashion Jewellery
          </span>
        </Link>

        {/* Desktop Navigation */}
        <nav className="hidden lg:flex items-center space-x-8">
          <NavLink to="/shop" label="Collections" isActive={isActiveRoute('/shop')} />
          <NavLink to="/tools" label="Tools" isActive={isActiveRoute('/tools')} />
          <NavLink to="/wonders-of-gold" label="Wonders of Gold" isActive={isActiveRoute('/wonders-of-gold')} />
          <NavLink to="/videos" label="Videos" isActive={isActiveRoute('/videos')} />
          <NavLink to="/blog" label="Journal" isActive={isActiveRoute('/blog')} />
          <NavLink to="/events" label="Events" isActive={isActiveRoute('/events')} />
          <NavLink to="/contact" label="Contact" isActive={isActiveRoute('/contact')} />
        </nav>

        {/* Action Buttons */}
        <div className="flex items-center space-x-2">
          {/* <Button 
            variant="ghost" 
            size="icon" 
            className="text-foreground hover:text-primary hover:bg-primary/10 transition-all duration-300"
          >
            <Search size={20} />
          </Button> */}
          
          <Button 
            variant="ghost" 
            size="icon" 
            asChild
            className="text-foreground hover:text-primary hover:bg-primary/10 transition-all duration-300 hidden md:inline-flex"
          >
            <Link to="/wishlist">
              <Heart size={20} />
            </Link>
          </Button>
          
          {isAuthenticated ? (
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button 
                  variant="ghost" 
                  size="icon" 
                  className="text-foreground hover:text-primary hover:bg-primary/10 transition-all duration-300 hidden md:inline-flex"
                >
                  <User size={20} />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" className="w-56">
                <DropdownMenuLabel className="font-normal">
                  <div className="flex flex-col space-y-1">
                    <p className="text-sm font-medium leading-none">{user?.name}</p>
                    <p className="text-xs leading-none text-muted-foreground">
                      Welcome back!
                    </p>
                  </div>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem asChild>
                  {isFilamentRedirectPath(dashboardPath) ? (
                    <a href={getFilamentRedirectUrl(dashboardPath)} className="cursor-pointer">
                      <Settings className="mr-2 h-4 w-4" />
                      <span>Dashboard</span>
                    </a>
                  ) : (
                    <Link to={dashboardPath} className="cursor-pointer">
                      <Settings className="mr-2 h-4 w-4" />
                      <span>Dashboard</span>
                    </Link>
                  )}
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem 
                  onClick={logout}
                  className="cursor-pointer text-red-600 focus:text-red-600"
                >
                  <LogOut className="mr-2 h-4 w-4" />
                  <span>Log out</span>
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          ) : (
            <div className="hidden md:flex items-center space-x-2">
              <Button 
                variant="ghost" 
                asChild
                className="text-foreground hover:text-primary hover:bg-primary/10 transition-all duration-300"
              >
                <Link to="/login">Sign In</Link>
              </Button>
              <Button 
                asChild
                className="bg-primary hover:bg-primary/90 text-primary-foreground transition-all duration-300"
              >
                <Link to="/register">Sign Up</Link>
              </Button>
            </div>
          )}
          
          <Button 
            variant="ghost" 
            size="icon" 
            onClick={toggleMenu} 
            className="lg:hidden text-foreground hover:text-primary hover:bg-primary/10 transition-all duration-300"
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </Button>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMenuOpen && (
        <div className="lg:hidden absolute top-full left-0 w-full bg-background/95 backdrop-blur-lg shadow-xl border-b border-border/50 animate-accordion-down">
          <nav className="flex flex-col items-center space-y-6 p-8">
            <MobileNavLink to="/shop" label="Collections" onClick={toggleMenu} />
            <MobileNavLink to="/tools" label="Tools" onClick={toggleMenu} />
            <MobileNavLink to="/wonders-of-gold" label="Wonders of Gold" onClick={toggleMenu} />
            <MobileNavLink to="/videos" label="Videos" onClick={toggleMenu} />
            <MobileNavLink to="/blog" label="Journal" onClick={toggleMenu} />
            <MobileNavLink to="/events" label="Events" onClick={toggleMenu} />
            <MobileNavLink to="/contact" label="Contact" onClick={toggleMenu} />
            <div className="pt-4 border-t border-border/30 w-full text-center space-y-4">
              <MobileNavLink to="/about" label="About Us" onClick={toggleMenu} />
              {isAuthenticated ? (
                <>
                  <div className="text-sm text-muted-foreground py-2">
                    Welcome, {user?.name}
                  </div>
                  {isFilamentRedirectPath(dashboardPath) ? (
                    <a
                      href={getFilamentRedirectUrl(dashboardPath)}
                      onClick={toggleMenu}
                      className="text-xl font-inter font-medium text-foreground hover:text-primary transition-colors duration-300 w-full text-center py-2"
                    >
                      Dashboard
                    </a>
                  ) : (
                    <MobileNavLink to={dashboardPath} label="Dashboard" onClick={toggleMenu} />
                  )}
                  <Button 
                    onClick={() => { logout(); toggleMenu(); }}
                    variant="ghost" 
                    className="w-full text-red-600 hover:text-red-600 hover:bg-red-50"
                  >
                    <LogOut className="mr-2 h-4 w-4" />
                    Log out
                  </Button>
                </>
              ) : (
                <div className="flex flex-col space-y-2">
                  <Button asChild variant="outline" className="w-full">
                    <Link to="/login" onClick={toggleMenu}>Sign In</Link>
                  </Button>
                  <Button asChild className="w-full">
                    <Link to="/register" onClick={toggleMenu}>Sign Up</Link>
                  </Button>
                </div>
              )}
            </div>
          </nav>
        </div>
      )}
    </header>
  );
};

interface NavLinkProps { to: string; label: string; isActive: boolean; }
const NavLink: React.FC<NavLinkProps> = ({ to, label, isActive }) => (
  <Link 
    to={to} 
    className={`relative font-inter font-medium transition-all duration-300 hover:text-primary group ${
      isActive ? 'text-primary' : 'text-foreground'
    }`}
  >
    {label}
    <span className={`absolute -bottom-2 left-0 h-0.5 bg-primary transition-all duration-300 ${
      isActive ? 'w-full' : 'w-0 group-hover:w-full'
    }`} />
  </Link>
);

interface MobileNavLinkProps { to: string; label: string; onClick: () => void; }
const MobileNavLink: React.FC<MobileNavLinkProps> = ({ to, label, onClick }) => (
  <Link 
    to={to} 
    onClick={onClick} 
    className="text-xl font-inter font-medium text-foreground hover:text-primary transition-colors duration-300 w-full text-center py-2"
  >
    {label}
  </Link>
);

export default Header;
