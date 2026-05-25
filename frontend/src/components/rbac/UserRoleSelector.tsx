
import React, { useState } from 'react';
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { X, Plus } from 'lucide-react';
import { toast } from 'sonner';

interface Role {
  id: string;
  name: string;
  description: string;
}

interface UserRoleSelectorProps {
  selectedRoles: string[];
  onChange: (roles: string[]) => void;
}

const UserRoleSelector: React.FC<UserRoleSelectorProps> = ({ selectedRoles, onChange }) => {
  const [selectedRoleId, setSelectedRoleId] = useState<string>("");

  // Mock roles data
  const availableRoles: Role[] = [
    { id: '1', name: 'Admin', description: 'Full access to all features' },
    { id: '2', name: 'Editor', description: 'Can create and edit content' },
    { id: '3', name: 'Author', description: 'Can create and edit own content' },
    { id: '4', name: 'Subscriber', description: 'Read-only access' },
    { id: '5', name: 'SEO Manager', description: 'Can manage SEO settings' }
  ];

  const handleAddRole = () => {
    if (!selectedRoleId) return;
    
    if (selectedRoles.includes(selectedRoleId)) {
      toast.error("This role is already assigned");
      return;
    }
    
    const newRoles = [...selectedRoles, selectedRoleId];
    onChange(newRoles);
    setSelectedRoleId("");
  };

  const handleRemoveRole = (roleId: string) => {
    const newRoles = selectedRoles.filter(id => id !== roleId);
    onChange(newRoles);
  };

  const getRoleName = (roleId: string): string => {
    const role = availableRoles.find(role => role.id === roleId);
    return role ? role.name : 'Unknown Role';
  };

  return (
    <div className="space-y-4">
      <Label>User Roles</Label>
      
      <div className="flex flex-wrap gap-2 mb-4">
        {selectedRoles.length === 0 ? (
          <span className="text-muted-foreground text-sm">No roles assigned</span>
        ) : (
          selectedRoles.map(roleId => (
            <Badge key={roleId} variant="secondary" className="flex items-center gap-1">
              {getRoleName(roleId)}
              <button 
                type="button" 
                onClick={() => handleRemoveRole(roleId)}
                className="ml-1 hover:text-destructive focus:outline-none"
              >
                <X size={14} />
                <span className="sr-only">Remove role</span>
              </button>
            </Badge>
          ))
        )}
      </div>
      
      <div className="flex gap-2">
        <Select value={selectedRoleId} onValueChange={setSelectedRoleId}>
          <SelectTrigger className="w-full">
            <SelectValue placeholder="Select a role" />
          </SelectTrigger>
          <SelectContent>
            {availableRoles.map(role => (
              <SelectItem key={role.id} value={role.id}>
                <div>
                  <span>{role.name}</span>
                  <span className="text-xs text-muted-foreground block">{role.description}</span>
                </div>
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        <Button 
          type="button" 
          size="icon" 
          variant="secondary"
          onClick={handleAddRole}
          disabled={!selectedRoleId}
        >
          <Plus size={16} />
          <span className="sr-only">Add role</span>
        </Button>
      </div>
    </div>
  );
};

export default UserRoleSelector;
