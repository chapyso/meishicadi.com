import React from 'react';

interface StatCardProps {
  title: string;
  value: string | number;
  description: string;
  icon: string;
  trend?: {
    value: string;
    isPositive: boolean;
  };
}

const StatCard: React.FC<StatCardProps> = ({ 
  title, 
  value, 
  description, 
  icon, 
  trend 
}) => {
  return (
    <div className="bg-white rounded-2xl p-6 shadow-card hover:shadow-hover transition-all duration-200 border border-gray-100">
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
            <span className="text-lg">{icon}</span>
          </div>
          <div>
            <h3 className="text-card-label text-gray-900 font-medium">{title}</h3>
          </div>
        </div>
        {trend && (
          <div className={`text-sm font-medium ${
            trend.isPositive ? 'text-green-600' : 'text-red-600'
          }`}>
            {trend.isPositive ? '+' : ''}{trend.value}
          </div>
        )}
      </div>
      
      <div className="space-y-2">
        <div className="text-card-value text-gray-900">
          {value}
        </div>
        <p className="text-secondary-label text-gray-500">
          {description}
        </p>
      </div>
    </div>
  );
};

export default StatCard;
