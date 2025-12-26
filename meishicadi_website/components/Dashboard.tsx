import React, { useState } from 'react';
import Sidebar from './Sidebar';
import Header from './Header';
import StatCard from './StatCard';
import AnalyticsSection from './AnalyticsSection';

const Dashboard: React.FC = () => {
  const [activeSidebarItem, setActiveSidebarItem] = useState('Dashboard');

  const handleSidebarItemClick = (item: string) => {
    setActiveSidebarItem(item);
  };

  const getCurrentGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good Morning';
    if (hour < 17) return 'Good Afternoon';
    return 'Good Evening';
  };

  return (
    <div className="min-h-screen bg-dark-900">
      {/* Sidebar */}
      <Sidebar 
        activeItem={activeSidebarItem} 
        onItemClick={handleSidebarItemClick} 
      />
      
      {/* Main Content */}
      <div className="ml-64">
        {/* Header Section */}
        <Header 
          userName="Neha Siddique" 
          currentTime={getCurrentGreeting()} 
        />
        
        {/* Statistics Cards Section */}
        <div className="px-8 pb-8">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard
              title="Total Cards"
              value="154"
              description="Active business cards"
              icon="💳"
              trend={{ value: "12%", isPositive: true }}
            />
            <StatCard
              title="Total Appointments"
              value="0"
              description="Scheduled meetings"
              icon="📅"
            />
            <StatCard
              title="Total Admin"
              value="5"
              description="Team members"
              icon="👥"
              trend={{ value: "2", isPositive: true }}
            />
            <StatCard
              title="Tap Analytics"
              value="1,247"
              description="Total interactions"
              icon="👆"
              trend={{ value: "8%", isPositive: true }}
            />
          </div>
        </div>
        
        {/* Analytics Section */}
        <AnalyticsSection />
      </div>
    </div>
  );
};

export default Dashboard;
