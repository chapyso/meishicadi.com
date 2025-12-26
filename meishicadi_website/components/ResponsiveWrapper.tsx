import React, { useState, useEffect } from 'react';

interface ResponsiveWrapperProps {
  children: React.ReactNode;
}

const ResponsiveWrapper: React.FC<ResponsiveWrapperProps> = ({ children }) => {
  const [isMobile, setIsMobile] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  useEffect(() => {
    const checkScreenSize = () => {
      setIsMobile(window.innerWidth < 1024);
    };

    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);

    return () => window.removeEventListener('resize', checkScreenSize);
  }, []);

  return (
    <div className="relative">
      {/* Mobile Sidebar Overlay */}
      {isMobile && sidebarOpen && (
        <div 
          className="fixed inset-0 bg-black bg-opacity-50 z-40"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Mobile Menu Button */}
      {isMobile && (
        <button
          onClick={() => setSidebarOpen(!sidebarOpen)}
          className="fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg lg:hidden"
        >
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      )}

      {/* Main Content with Responsive Margins */}
      <div className={`transition-all duration-300 ${
        isMobile ? 'ml-0' : 'ml-64'
      }`}>
        {React.Children.map(children, (child) => {
          if (React.isValidElement(child)) {
            return React.cloneElement(child, {
              isMobile,
              sidebarOpen,
              setSidebarOpen,
            } as any);
          }
          return child;
        })}
      </div>
    </div>
  );
};

export default ResponsiveWrapper;
