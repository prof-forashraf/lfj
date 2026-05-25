
import React, { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { toast } from 'sonner';

interface Permission {
  id: string;
  name: string;
  description: string;
  group: string;
}

interface RoleFormProps {
  role?: {
    id: string;
    name: string;
    description: string;
    permissions: string[];
  };
  onSubmit: (data: any) => void;
}

const RoleForm: React.FC<RoleFormProps> = ({ role, onSubmit }) => {
  const [name, setName] = useState(role?.name || '');
  const [description, setDescription] = useState(role?.description || '');
  const [selectedPermissions, setSelectedPermissions] = useState<string[]>(role?.permissions || []);
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Mock permissions data
  const permissions: Permission[] = [
    // Blog permissions
    { id: 'blog.view', name: 'View Posts', description: 'Can view blog posts', group: 'Blog' },
    { id: 'blog.create', name: 'Create Posts', description: 'Can create blog posts', group: 'Blog' },
    { id: 'blog.edit', name: 'Edit Posts', description: 'Can edit blog posts', group: 'Blog' },
    { id: 'blog.delete', name: 'Delete Posts', description: 'Can delete blog posts', group: 'Blog' },
    { id: 'blog.publish', name: 'Publish Posts', description: 'Can publish blog posts', group: 'Blog' },
    
    // User permissions
    { id: 'users.view', name: 'View Users', description: 'Can view users', group: 'Users' },
    { id: 'users.create', name: 'Create Users', description: 'Can create users', group: 'Users' },
    { id: 'users.edit', name: 'Edit Users', description: 'Can edit users', group: 'Users' },
    { id: 'users.delete', name: 'Delete Users', description: 'Can delete users', group: 'Users' },
    
    // Role permissions
    { id: 'roles.view', name: 'View Roles', description: 'Can view roles', group: 'Roles' },
    { id: 'roles.create', name: 'Create Roles', description: 'Can create roles', group: 'Roles' },
    { id: 'roles.edit', name: 'Edit Roles', description: 'Can edit roles', group: 'Roles' },
    { id: 'roles.delete', name: 'Delete Roles', description: 'Can delete roles', group: 'Roles' },
    
    // Settings permissions
    { id: 'settings.view', name: 'View Settings', description: 'Can view settings', group: 'Settings' },
    { id: 'settings.edit', name: 'Edit Settings', description: 'Can edit settings', group: 'Settings' },
  ];

  // Group permissions by category
  const permissionGroups = permissions.reduce((groups: Record<string, Permission[]>, permission) => {
    if (!groups[permission.group]) {
      groups[permission.group] = [];
    }
    groups[permission.group].push(permission);
    return groups;
  }, {});

  const handleTogglePermission = (id: string) => {
    setSelectedPermissions(prev => 
      prev.includes(id) 
        ? prev.filter(p => p !== id)
        : [...prev, id]
    );
  };

  const handleToggleGroup = (group: string) => {
    const groupPermissionIds = permissions
      .filter(p => p.group === group)
      .map(p => p.id);
    
    const allSelected = groupPermissionIds.every(id => selectedPermissions.includes(id));
    
    if (allSelected) {
      setSelectedPermissions(prev => prev.filter(id => !groupPermissionIds.includes(id)));
    } else {
      const newSelected = [...selectedPermissions];
      groupPermissionIds.forEach(id => {
        if (!newSelected.includes(id)) {
          newSelected.push(id);
        }
      });
      setSelectedPermissions(newSelected);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    
    // Validate
    if (!name.trim()) {
      toast.error("Role name is required");
      setIsSubmitting(false);
      return;
    }
    
    // Submit form data
    onSubmit({
      name,
      description,
      permissions: selectedPermissions
    });
    
    toast.success(`Role ${role ? 'updated' : 'created'} successfully`);
    setIsSubmitting(false);
  };

  return (
    <form onSubmit={handleSubmit}>
      <Card>
        <CardHeader>
          <CardTitle>{role ? 'Edit Role' : 'Create New Role'}</CardTitle>
          <CardDescription>
            {role 
              ? 'Update role details and permissions' 
              : 'Define a new role with specific permissions'}
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">Role Name</Label>
              <Input
                id="name"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="Enter role name"
                className="mt-1"
                required
              />
            </div>
            <div>
              <Label htmlFor="description">Description</Label>
              <Input
                id="description"
                value={description}
                onChange={(e) => setDescription(e.target.value)}
                placeholder="Enter role description"
                className="mt-1"
              />
            </div>
          </div>

          <div>
            <h3 className="font-medium text-lg mb-4">Role Permissions</h3>
            
            <div className="space-y-6">
              {Object.entries(permissionGroups).map(([group, perms]) => {
                const allSelected = perms.every(p => selectedPermissions.includes(p.id));
                const someSelected = perms.some(p => selectedPermissions.includes(p.id));
                
                return (
                  <div key={group} className="border rounded-md p-4">
                    <div className="flex items-center mb-4">
                      <Checkbox
                        id={`group-${group}`}
                        checked={allSelected}
                        onCheckedChange={() => handleToggleGroup(group)}
                        className="mr-2"
                        ref={null}
                        data-state={allSelected ? "checked" : someSelected ? "indeterminate" : "unchecked"}
                      />
                      <Label
                        htmlFor={`group-${group}`}
                        className="font-medium"
                      >
                        {group}
                      </Label>
                    </div>
                    
                    <div className="ml-6 grid gap-3">
                      {perms.map(permission => (
                        <div key={permission.id} className="flex items-center">
                          <Checkbox
                            id={permission.id}
                            checked={selectedPermissions.includes(permission.id)}
                            onCheckedChange={() => handleTogglePermission(permission.id)}
                            className="mr-2"
                          />
                          <Label
                            htmlFor={permission.id}
                            className="flex flex-col"
                          >
                            <span>{permission.name}</span>
                            <span className="text-xs text-muted-foreground">{permission.description}</span>
                          </Label>
                        </div>
                      ))}
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </CardContent>
        <CardFooter className="flex justify-between">
          <Button variant="outline" type="button" onClick={() => window.history.back()}>
            Cancel
          </Button>
          <Button type="submit" disabled={isSubmitting}>
            {isSubmitting ? 'Saving...' : role ? 'Update Role' : 'Create Role'}
          </Button>
        </CardFooter>
      </Card>
    </form>
  );
};

export default RoleForm;
