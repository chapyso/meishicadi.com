import React from 'react';

interface SidebarProps {
  activeItem?: string;
  onItemClick?: (item: string) => void;
}

const Sidebar: React.FC<SidebarProps> = ({ activeItem = 'Dashboard', onItemClick }) => {
  const menuItems = [
    { id: 'Dashboard', icon: '📊', label: 'Dashboard' },
    { id: 'Create Your Card', icon: '➕', label: 'Create Your Card' },
    { id: 'Manage Admins', icon: '👥', label: 'Manage Admins' },
    { id: 'Appointments', icon: '📅', label: 'Appointments' },
    { id: 'Wallet', icon: '💰', label: 'Wallet' },
    { id: 'Leads', icon: '🎯', label: 'Leads' },
    { id: 'Contact Support', icon: '💬', label: 'Contact Support' },
    { id: 'Bulk Transfer', icon: '📤', label: 'Bulk Transfer' },
    { id: 'Settings', icon: '⚙️', label: 'Settings' },
  ];

  return (
    <div className="fixed left-0 top-0 h-full w-64 bg-dark-900 border-r border-gray-700 z-50">
      {/* Brand Section */}
      <div className="p-6 border-b border-gray-700">
        <div className="flex items-center space-x-3">
          <div className="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
            <span className="text-white font-bold text-sm">C</span>
          </div>
          <div>
            <h2 className="text-white font-semibold text-sm">CONSULUM</h2>
            <p className="text-gray-400 text-xs">GOVERNMENT ADVISORY</p>
          </div>
        </div>
      </div>

      {/* Navigation Menu */}
      <nav className="p-4 space-y-2">
        {menuItems.map((item) => (
          <button
            key={item.id}
            onClick={() => onItemClick?.(item.id)}
            className={`w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-left transition-all duration-200 ${
              activeItem === item.id
                ? 'bg-primary-500 text-white shadow-lg'
                : 'text-gray-300 hover:bg-gray-800 hover:text-white'
            }`}
          >
            <span className="text-lg">{item.icon}</span>
            <span className="font-medium text-sm">{item.label}</span>
          </button>
        ))}
      </nav>

      {/* Footer */}
      <div className="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
        <p className="text-gray-400 text-xs text-center">
          Copyright © Meishicadi by Chapy Inc 2025
        </p>
      </div>
    </div>
  );
};

export default Sidebar;
