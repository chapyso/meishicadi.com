import React from 'react';

interface AnalyticsPanelProps {
  title: string;
  timeRange?: string;
  children?: React.ReactNode;
  isEmpty?: boolean;
}

const AnalyticsPanel: React.FC<AnalyticsPanelProps> = ({ 
  title, 
  timeRange, 
  children, 
  isEmpty = false 
}) => {
  return (
    <div className="bg-white rounded-2xl p-6 shadow-card border border-gray-100">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-gray-900">{title}</h3>
        {timeRange && (
          <span className="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
            {timeRange}
          </span>
        )}
      </div>
      
      {isEmpty ? (
        <div className="flex items-center justify-center h-48 text-gray-400">
          <div className="text-center">
            <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <span className="text-2xl">📊</span>
            </div>
            <p className="text-sm">No data available</p>
          </div>
        </div>
      ) : (
        <div className="h-48">
          {children}
        </div>
      )}
    </div>
  );
};

const TapAnalyticsCard: React.FC = () => {
  return (
    <div className="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-6 text-white shadow-card">
      <div className="flex items-center justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
            <span className="text-lg">📈</span>
          </div>
          <h3 className="text-lg font-semibold">Tap Analytics</h3>
        </div>
      </div>
      
      <div className="space-y-4">
        <div className="flex space-x-3">
          <button className="flex-1 px-4 py-2 bg-white bg-opacity-20 text-white font-medium rounded-lg hover:bg-opacity-30 transition-all duration-200">
            View
          </button>
          <button className="flex-1 px-4 py-2 bg-white bg-opacity-20 text-white font-medium rounded-lg hover:bg-opacity-30 transition-all duration-200">
            Analytics
          </button>
        </div>
      </div>
    </div>
  );
};

const StorageStatusCard: React.FC = () => {
  const usedStorage = 90.24;
  const totalStorage = 500;
  const percentage = (usedStorage / totalStorage) * 100;

  return (
    <div className="bg-white rounded-2xl p-6 shadow-card border border-gray-100">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-gray-900">Storage Status</h3>
        <span className="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
          Last 15 Days
        </span>
      </div>
      
      <div className="space-y-4">
        <div className="flex items-center justify-between">
          <span className="text-2xl font-bold text-gray-900">
            {usedStorage}MB
          </span>
          <span className="text-sm text-gray-500">
            of {totalStorage}MB
          </span>
        </div>
        
        <div className="w-full bg-gray-200 rounded-full h-2">
          <div 
            className="bg-primary-500 h-2 rounded-full transition-all duration-300"
            style={{ width: `${percentage}%` }}
          ></div>
        </div>
        
        <p className="text-sm text-gray-600">
          {percentage.toFixed(1)}% of storage used
        </p>
      </div>
    </div>
  );
};

const AnalyticsSection: React.FC = () => {
  return (
    <div className="ml-64 p-8 pt-0">
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        {/* Appointments */}
        <AnalyticsPanel title="Appointments" isEmpty={true} />
        
        {/* Platform Analytics */}
        <AnalyticsPanel title="Platform Analytics" timeRange="Last 7 Days" isEmpty={true} />
        
        {/* Tap Analytics */}
        <TapAnalyticsCard />
        
        {/* Browser Usage */}
        <AnalyticsPanel title="Browser Usage" timeRange="Last 15 Days" isEmpty={true} />
        
        {/* Device Usage */}
        <AnalyticsPanel title="Device Usage" timeRange="Last 15 Days" isEmpty={true} />
        
        {/* Storage Status */}
        <StorageStatusCard />
      </div>
    </div>
  );
};

export default AnalyticsSection;
