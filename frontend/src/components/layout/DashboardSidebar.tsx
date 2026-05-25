
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { 
  LayoutDashboard, 
  FileText, 
  Users, 
  Tag, 
  Settings, 
  UserCheck, 
  Shield, 
  ChevronRight, 
  ChevronDown 
} from 'lucide-react';
import { cn } from '@/lib/utils';

interface SidebarLinkProps {
  to: string;
  icon: React.ElementType;
  label: string;
  active?: boolean;
  subItems?: { to: string; label: string }[];
}

const SidebarLink: React.FC<SidebarLinkProps> = ({ 
  to, 
  icon: Icon, 
  label, 
  active = false,
  subItems = [] 
}) => {
  const [expanded, setExpanded] = React.useState(false);
  const hasSubItems = subItems.length > 0;

  return (
    <div>
      <div 
        className={cn(
          "flex items-center px-4 py-3 text-sm font-medium rounded-md mb-1 transition-colors",
          active 
            ? "bg-sidebar-accent text-white" 
            : "text-sidebar-foreground hover:bg-sidebar-accent hover:bg-opacity-75"
        )}
        onClick={() => hasSubItems && setExpanded(!expanded)}
      >
        {hasSubItems ? (
          <>
            <Icon size={18} className="mr-3" />
            <span className="flex-1">{label}</span>
            {expanded ? (
              <ChevronDown size={16} />
            ) : (
              <ChevronRight size={16} />
            )}
          </>
        ) : (
          <Link to={to} className="flex items-center w-full">
            <Icon size={18} className="mr-3" />
            <span>{label}</span>
          </Link>
        )}
      </div>

      {hasSubItems && expanded && (
        <div className="ml-4 pl-4 border-l border-sidebar-border space-y-1">
          {subItems.map((item) => (
            <Link 
              key={item.to}
              to={item.to} 
              className="flex items-center px-3 py-2 text-sm text-sidebar-foreground hover:text-white rounded-md"
            >
              {item.label}
            </Link>
          ))}
        </div>
      )}
    </div>
  );
};

interface DashboardSidebarProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
}

const DashboardSidebar: React.FC<DashboardSidebarProps> = ({ isOpen, setIsOpen }) => {
  const location = useLocation();

  const sidebarLinks = [
    { 
      to: '/dashboard', 
      icon: LayoutDashboard, 
      label: 'Dashboard' 
    },
    { 
      to: '#', 
      icon: FileText, 
      label: 'Blog', 
      subItems: [
        { to: '/dashboard/posts', label: 'All Posts' },
        { to: '/dashboard/posts/create', label: 'Create Post' },
        { to: '/dashboard/categories', label: 'Categories' },
        { to: '/dashboard/tags', label: 'Tags' }
      ]
    },
    { 
      to: '#', 
      icon: Users, 
      label: 'Users', 
      subItems: [
        { to: '/dashboard/users', label: 'All Users' },
        { to: '/dashboard/users/create', label: 'Create User' }
      ] 
    },
    { 
      to: '#', 
      icon: Shield, 
      label: 'Roles & Permissions', 
      subItems: [
        { to: '/dashboard/roles', label: 'All Roles' },
        { to: '/dashboard/roles/create', label: 'Create Role' },
        { to: '/dashboard/permissions', label: 'Permissions' }
      ] 
    },
    { 
      to: '/dashboard/settings', 
      icon: Settings, 
      label: 'Settings' 
    }
  ];

  return (
    <>
      {/* Overlay */}
      {isOpen && (
        <div 
          className="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
          onClick={() => setIsOpen(false)}
        />
      )}

      {/* Sidebar */}
      <div 
        className={cn(
          "fixed inset-y-0 left-0 z-30 w-64 bg-sidebar flex flex-col transition-transform transform-gpu duration-300 ease-in-out",
          isOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0",
          "bg-sidebar"
        )}
      >
        {/* Logo */}
        <div className="h-16 flex items-center px-6 border-b border-sidebar-border">
          <Link to="/" className="flex items-center space-x-2">
            <div className="w-8 h-8 bg-primary rounded flex items-center justify-center">
              <span className="font-bold text-white">LF</span>
            </div>
            <span className="text-xl font-bold text-white">Latest Fashion Jewellery</span>
          </Link>
        </div>

        {/* Navigation */}
        <nav className="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
          {sidebarLinks.map((link) => (
            <SidebarLink
              key={link.label}
              to={link.to}
              icon={link.icon}
              label={link.label}
              active={link.to === location.pathname}
              subItems={link.subItems}
            />
          ))}
        </nav>
      </div>
    </>
  );
};

export default DashboardSidebar;
